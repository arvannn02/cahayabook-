<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BukuKeluar;
use App\Models\Buku;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class BukuKeluarController extends Controller
{
    public function index(Request $request)
    {
        $query = BukuKeluar::with(['details.buku.penerbit', 'user'])
            ->orderBy('tanggal_keluar', 'desc')
            ->orderBy('id', 'desc');
        
        // Search functionality
        if ($request->filled('search')) {
            $search = trim($request->search);
            $cleanSearch = ltrim($search, '#');
            $query->where(function($q) use ($search, $cleanSearch) {
                $q->where('no_transaksi', 'like', "%{$search}%")
                  ->orWhere('no_transaksi', 'like', "%{$cleanSearch}%")
                  ->orWhere('tujuan', 'like', "%{$search}%")
                  ->orWhere('expedisi', 'like', "%{$search}%")
                  ->orWhere('keterangan', 'like', "%{$search}%")
                  ->orWhere('status', 'like', "%{$search}%")
                  ->orWhereHas('details.buku', function($bq) use ($search) {
                      $bq->where('judul', 'like', "%{$search}%")
                         ->orWhere('isbn', 'like', "%{$search}%")
                         ->orWhereHas('penerbit', function($pq) use ($search) {
                             $pq->where('nama_penerbit', 'like', "%{$search}%");
                         });
                  });
            });
        }

        // Periode filter
        $periode = $request->get('periode', '');
        if ($periode && $periode !== 'semua') {
            if ($periode === 'bulan_ini') {
                $query->whereMonth('tanggal_keluar', Carbon::now()->month)
                      ->whereYear('tanggal_keluar', Carbon::now()->year);
            } elseif ($periode === 'bulan_lalu') {
                $lastMonth = Carbon::now()->subMonth();
                $query->whereMonth('tanggal_keluar', $lastMonth->month)
                      ->whereYear('tanggal_keluar', $lastMonth->year);
            } elseif ($periode === 'tahun_2026') {
                $query->whereYear('tanggal_keluar', 2026);
            } elseif ($periode === 'tahun_2023') {
                $query->whereYear('tanggal_keluar', 2023);
            }
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Tujuan filter
        if ($request->filled('tujuan')) {
            $query->where('tujuan', $request->tujuan);
        }

        // Ekspedisi filter
        if ($request->filled('expedisi')) {
            $query->where('expedisi', $request->expedisi);
        }

        $bukuKeluar = $query->paginate(6)->withQueryString();
        $buku = Buku::with(['stok', 'penerbit'])->orderBy('judul')->get();

        $totalKeluarBulanIni = (int) DB::table('buku_keluar')
            ->join('detail_buku_keluar', 'buku_keluar.id', '=', 'detail_buku_keluar.id_buku_keluar')
            ->whereMonth('buku_keluar.tanggal_keluar', Carbon::now()->month)
            ->whereYear('buku_keluar.tanggal_keluar', Carbon::now()->year)
            ->sum('detail_buku_keluar.jumlah');

        $transaksiHariIni = BukuKeluar::whereDate('tanggal_keluar', Carbon::today())->count();
        $menungguPengiriman = BukuKeluar::whereIn('status', ['Menunggu', 'Diproses', 'Dikirim'])->count();
        $tujuanPopuler = BukuKeluar::select('tujuan', DB::raw('count(*) as total'))
            ->whereNotNull('tujuan')
            ->where('tujuan', '!=', '')
            ->groupBy('tujuan')
            ->orderByDesc('total')
            ->value('tujuan') ?? 'Toko Cabang';

        // Filter options for dropdowns
        $daftarTujuan = BukuKeluar::whereNotNull('tujuan')
            ->where('tujuan', '!=', '')
            ->distinct()
            ->pluck('tujuan');

        $daftarExpedisi = BukuKeluar::whereNotNull('expedisi')
            ->where('expedisi', '!=', '')
            ->distinct()
            ->pluck('expedisi');

        $periodeLabels = [
            'bulan_ini'  => 'Bulan Ini',
            'bulan_lalu' => 'Bulan Lalu',
            'tahun_2026' => 'Tahun 2026',
            'tahun_2023' => 'Tahun 2023 (Database Awal)',
            'semua'      => 'Semua Periode',
        ];
        $activePeriodeLabel = $periodeLabels[$periode] ?? 'Semua Periode';
        $nextNoTransaksi = BukuKeluar::generateNoTransaksi();

        return view('buku_keluar.index', compact(
            'bukuKeluar', 
            'buku', 
            'totalKeluarBulanIni', 
            'transaksiHariIni', 
            'menungguPengiriman', 
            'tujuanPopuler',
            'daftarTujuan',
            'daftarExpedisi',
            'activePeriodeLabel',
            'nextNoTransaksi'
        ));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'no_transaksi' => ['nullable', 'string', 'max:50'],
            'tanggal_keluar' => ['required', 'date'], 'keterangan' => ['nullable', 'string'], 'tujuan' => ['nullable', 'string', 'max:150'], 'expedisi' => ['nullable', 'string', 'max:100'], 'status' => ['required', 'string', 'max:30'],
            'id_buku' => ['required', 'exists:buku,id'], 'jumlah' => ['required', 'integer', 'min:1'], 'harga_satuan' => ['required', 'numeric', 'min:0'],
        ]);

        if (empty($data['no_transaksi']) || BukuKeluar::where('no_transaksi', $data['no_transaksi'])->exists()) {
            $data['no_transaksi'] = BukuKeluar::generateNoTransaksi();
        }

        DB::transaction(function () use ($data) {
            $buku = Buku::findOrFail($data['id_buku']); $stok = $buku->stok;
            if (!$stok || $stok->stok_sisa < $data['jumlah']) abort(422, 'Stok buku tidak mencukupi.');
            $header = BukuKeluar::create(['no_transaksi' => $data['no_transaksi'], 'id_user' => Auth::id() ?? 1, 'tanggal_keluar' => $data['tanggal_keluar'], 'keterangan' => $data['keterangan'] ?? null, 'tujuan' => $data['tujuan'] ?? null, 'expedisi' => $data['expedisi'] ?? null, 'status' => $data['status']]);
            $header->details()->create(['id_buku' => $data['id_buku'], 'jumlah' => $data['jumlah'], 'harga_satuan' => $data['harga_satuan'], 'subtotal' => $data['jumlah'] * $data['harga_satuan']]);
            $stok->increment('stok_keluar', $data['jumlah']); $stok->decrement('stok_sisa', $data['jumlah']);
        });
        return back()->with('success', 'Transaksi buku keluar berhasil ditambahkan.');
    }

    public function update(Request $request, BukuKeluar $bukuKeluar)
    {
        $data = $request->validate(['tanggal_keluar' => ['required', 'date'], 'keterangan' => ['nullable', 'string'], 'tujuan' => ['nullable', 'string', 'max:150'], 'expedisi' => ['nullable', 'string', 'max:100'], 'status' => ['required', 'string', 'max:30']]);
        $bukuKeluar->update($data);
        return back()->with('success', 'Transaksi buku keluar berhasil diperbarui.');
    }

    public function destroy(BukuKeluar $bukuKeluar)
    {
        DB::transaction(function () use ($bukuKeluar) {
            foreach ($bukuKeluar->details as $detail) {
                $stok = $detail->buku->stok;
                if ($stok) { $stok->decrement('stok_keluar', $detail->jumlah); $stok->increment('stok_sisa', $detail->jumlah); }
            }
            $bukuKeluar->details()->delete(); $bukuKeluar->delete();
        });
        return back()->with('success', 'Transaksi buku keluar berhasil dihapus.');
    }
}
