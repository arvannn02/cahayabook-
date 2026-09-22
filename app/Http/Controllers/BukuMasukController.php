<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BukuMasuk;
use App\Models\Buku;
use App\Models\RakBuku;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class BukuMasukController extends Controller
{
    public function index(Request $request)
    {
        $query = BukuMasuk::with(['details.buku.penerbit', 'details.buku.rak', 'details.buku.kategori'])->orderBy('tanggal_masuk', 'desc')->orderBy('id', 'desc');

        // Search functionality
        if ($request->filled('search')) {
            $search = trim($request->search);
            $cleanSearch = ltrim($search, '#');
            $query->where(function ($q) use ($search, $cleanSearch) {
                $q->where('no_transaksi', 'like', "%{$search}%")
                  ->orWhere('no_transaksi', 'like', "%{$cleanSearch}%")
                  ->orWhere('keterangan', 'like', "%{$search}%")
                  ->orWhere('status', 'like', "%{$search}%")
                  ->orWhereHas('details.buku', function ($bq) use ($search) {
                      $bq->where('judul', 'like', "%{$search}%")
                         ->orWhere('isbn', 'like', "%{$search}%")
                         ->orWhereHas('penerbit', function ($pq) use ($search) {
                             $pq->where('nama_penerbit', 'like', "%{$search}%");
                         })
                         ->orWhereHas('rak', function ($rq) use ($search) {
                             $rq->where('kode_rak', 'like', "%{$search}%")
                                ->orWhere('lokasi', 'like', "%{$search}%");
                         });
                  });
            });
        }

        // Periode filter
        $periode = $request->get('periode', '');
        if ($periode && $periode !== 'semua') {
            if ($periode === 'bulan_ini') {
                $query->whereMonth('tanggal_masuk', Carbon::now()->month)
                      ->whereYear('tanggal_masuk', Carbon::now()->year);
            } elseif ($periode === 'bulan_lalu') {
                $lastMonth = Carbon::now()->subMonth();
                $query->whereMonth('tanggal_masuk', $lastMonth->month)
                      ->whereYear('tanggal_masuk', $lastMonth->year);
            } elseif ($periode === 'tahun_2026') {
                $query->whereYear('tanggal_masuk', 2026);
            } elseif ($periode === 'tahun_2023') {
                $query->whereYear('tanggal_masuk', 2023);
            }
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Penerbit filter
        if ($request->filled('penerbit')) {
            $penerbit = $request->penerbit;
            $query->whereHas('details.buku.penerbit', function ($q) use ($penerbit) {
                if (is_numeric($penerbit)) {
                    $q->where('id', $penerbit);
                } else {
                    $q->where('nama_penerbit', $penerbit);
                }
            });
        }

        // Rak Tujuan filter
        if ($request->filled('rak')) {
            $rak = $request->rak;
            $query->where(function ($q) use ($rak) {
                $q->where('keterangan', $rak)
                  ->orWhereHas('details.buku.rak', function ($rq) use ($rak) {
                      $rq->where('kode_rak', $rak);
                  });
            });
        }

        $bukuMasuk = $query->paginate(6)->withQueryString();

        // Calculate Stats (Genuine database metrics)
        $totalMasukBulanIni = DB::table('detail_buku_masuk')
            ->join('buku_masuk', 'detail_buku_masuk.id_buku_masuk', '=', 'buku_masuk.id')
            ->whereMonth('buku_masuk.tanggal_masuk', Carbon::now()->month)
            ->whereYear('buku_masuk.tanggal_masuk', Carbon::now()->year)
            ->sum('detail_buku_masuk.jumlah') ?: 0;

        $bukuBaruTerdaftar = Buku::count();
        $pendingCheck = BukuMasuk::where('status', '!=', 'Selesai')->count();

        // Auxiliary data for modals and dropdowns
        $buku = Buku::with('penerbit', 'rak')->orderBy('judul')->get();
        $rak = RakBuku::orderBy('kode_rak')->get();
        $daftarPenerbit = \App\Models\Penerbit::orderBy('nama_penerbit')->get();

        // Distinct rack values for filter dropdown
        $daftarRak = RakBuku::pluck('kode_rak')
            ->merge(BukuMasuk::whereNotNull('keterangan')->where('keterangan', '!=', '')->pluck('keterangan'))
            ->unique()
            ->filter()
            ->values();

        $periodeLabels = [
            'bulan_ini' => 'Bulan Ini',
            'bulan_lalu' => 'Bulan Lalu',
            'tahun_2026' => 'Tahun 2026',
            'tahun_2023' => 'Tahun 2023',
            'semua' => 'Semua Periode',
        ];
        $activePeriodeLabel = $periodeLabels[$periode] ?? 'Semua Periode';
        $nextNoTransaksi = BukuMasuk::generateNoTransaksi();

        return view('buku_masuk.index', compact(
            'bukuMasuk',
            'buku',
            'rak',
            'totalMasukBulanIni',
            'bukuBaruTerdaftar',
            'pendingCheck',
            'daftarPenerbit',
            'daftarRak',
            'activePeriodeLabel',
            'nextNoTransaksi'
        ));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'no_transaksi' => ['nullable', 'string', 'max:50'],
            'tanggal_masuk' => ['required', 'date'],
            'keterangan' => ['nullable', 'string', 'max:100'],
            'status' => ['required', 'string', 'max:30'],
            'id_buku' => ['required', 'exists:buku,id'],
            'jumlah' => ['required', 'integer', 'min:1'],
            'harga_satuan' => ['required', 'numeric', 'min:0'],
        ]);

        if (empty($data['no_transaksi']) || BukuMasuk::where('no_transaksi', $data['no_transaksi'])->exists()) {
            $data['no_transaksi'] = BukuMasuk::generateNoTransaksi();
        }

        DB::transaction(function () use ($data) {
            $header = BukuMasuk::create([
                'no_transaksi' => $data['no_transaksi'],
                'id_user' => Auth::id() ?? 1,
                'tanggal_masuk' => $data['tanggal_masuk'],
                'keterangan' => $data['keterangan'] ?? null,
                'status' => $data['status']
            ]);

            $header->details()->create([
                'id_buku' => $data['id_buku'],
                'jumlah' => $data['jumlah'],
                'harga_satuan' => $data['harga_satuan'],
                'subtotal' => $data['jumlah'] * $data['harga_satuan']
            ]);

            $bukuObj = Buku::find($data['id_buku']);
            if ($bukuObj) {
                $stok = $bukuObj->stok()->firstOrCreate([], [
                    'stok_masuk' => 0,
                    'stok_keluar' => 0,
                    'stok_sisa' => 0,
                    'min_stok' => 0
                ]);
                $stok->increment('stok_masuk', $data['jumlah']);
                $stok->increment('stok_sisa', $data['jumlah']);
            }
        });

        return back()->with('success', 'Transaksi buku masuk berhasil ditambahkan.');
    }

    public function update(Request $request, BukuMasuk $bukuMasuk)
    {
        $data = $request->validate([
            'tanggal_masuk' => ['required', 'date'],
            'keterangan' => ['nullable', 'string', 'max:100'],
            'status' => ['required', 'string', 'max:30']
        ]);

        $bukuMasuk->update($data);
        return back()->with('success', 'Transaksi buku masuk berhasil diperbarui.');
    }

    public function destroy(BukuMasuk $bukuMasuk)
    {
        DB::transaction(function () use ($bukuMasuk) {
            foreach ($bukuMasuk->details as $detail) {
                if ($detail->buku && $detail->buku->stok) {
                    $stok = $detail->buku->stok;
                    $stok->decrement('stok_masuk', $detail->jumlah);
                    $stok->decrement('stok_sisa', $detail->jumlah);
                }
            }
            $bukuMasuk->details()->delete();
            $bukuMasuk->delete();
        });

        return back()->with('success', 'Transaksi buku masuk berhasil dihapus.');
    }
}
