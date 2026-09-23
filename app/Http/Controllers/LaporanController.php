<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Buku;
use App\Models\BukuMasuk;
use App\Models\BukuKeluar;
use App\Models\StokBuku;
use App\Models\KategoriBuku;
use App\Models\RakBuku;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanController extends Controller
{
    public function getLaporanData(Request $request): array
    {
        // 1. Opsi filter Kategori dan Tahun yang tersedia di database
        $kategoriList = KategoriBuku::orderBy('nama_kategori')->get();

        $yearsMasuk = DB::table('buku_masuk')
            ->selectRaw('DISTINCT YEAR(tanggal_masuk) as tahun')
            ->pluck('tahun');
        $yearsKeluar = DB::table('buku_keluar')
            ->selectRaw('DISTINCT YEAR(tanggal_keluar) as tahun')
            ->pluck('tahun');

        $availableYears = $yearsMasuk->concat($yearsKeluar)
            ->unique()
            ->filter(fn($y) => (int)$y >= 2026)
            ->sortDesc()
            ->values()
            ->toArray();

        // Parameter Filter Aktif
        $selectedKategori = $request->get('kategori', 'all');
        $selectedTahun = $request->get('tahun', null);
        $selectedBulan = $request->get('bulan', 'all');
        $selectedTanggal = $request->filled('tanggal') ? $request->get('tanggal') : null;

        // Jika filter tanggal spesifik dipilih via kalender
        if ($selectedTanggal && $selectedTanggal !== 'all') {
            $parsedTime = strtotime($selectedTanggal);
            if ($parsedTime !== false) {
                $selectedTahun = (string) date('Y', $parsedTime);
                $selectedBulan = (int) date('n', $parsedTime);
            }
        }

        // Jika tahun tidak ditentukan di URL:
        // Gunakan tahun dari database (prioritaskan tahun pertama yang ada datanya)
        if ($selectedTahun === null) {
            if (count($availableYears) > 0) {
                $selectedTahun = (string) $availableYears[0];
            } else {
                $selectedTahun = 'all';
            }
        }

        // 2. Deteksi bulan-bulan yang memiliki data di tahun yang dipilih
        $namaBulanLengkap = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];

        $monthDataCounts = [];
        for ($m = 1; $m <= 12; $m++) {
            $monthDataCounts[$m] = [
                'nama' => $namaBulanLengkap[$m],
                'masuk_count' => 0,
                'keluar_count' => 0,
                'total_trx' => 0,
                'masuk_qty' => 0,
                'keluar_qty' => 0,
            ];
        }

        // Query hitungan transaksi per bulan
        $mCountMasukQuery = DB::table('buku_masuk')
            ->join('detail_buku_masuk', 'buku_masuk.id', '=', 'detail_buku_masuk.id_buku_masuk')
            ->join('buku', 'detail_buku_masuk.id_buku', '=', 'buku.id');
        $mCountKeluarQuery = DB::table('buku_keluar')
            ->join('detail_buku_keluar', 'buku_keluar.id', '=', 'detail_buku_keluar.id_buku_keluar')
            ->join('buku', 'detail_buku_keluar.id_buku', '=', 'buku.id');

        if ($selectedKategori && $selectedKategori !== 'all') {
            $mCountMasukQuery->where('buku.id_kategori', $selectedKategori);
            $mCountKeluarQuery->where('buku.id_kategori', $selectedKategori);
        }

        if ($selectedTahun && $selectedTahun !== 'all') {
            $mCountMasukQuery->whereYear('buku_masuk.tanggal_masuk', $selectedTahun);
            $mCountKeluarQuery->whereYear('buku_keluar.tanggal_keluar', $selectedTahun);
        }

        $masukPerBulanSummary = (clone $mCountMasukQuery)
            ->selectRaw('MONTH(buku_masuk.tanggal_masuk) as bulan, COUNT(DISTINCT buku_masuk.id) as trx_count, SUM(detail_buku_masuk.jumlah) as total_qty')
            ->groupBy('bulan')
            ->get();

        foreach ($masukPerBulanSummary as $item) {
            $b = (int) $item->bulan;
            if (isset($monthDataCounts[$b])) {
                $monthDataCounts[$b]['masuk_count'] = (int) $item->trx_count;
                $monthDataCounts[$b]['masuk_qty'] = (int) $item->total_qty;
                $monthDataCounts[$b]['total_trx'] += (int) $item->trx_count;
            }
        }

        $keluarPerBulanSummary = (clone $mCountKeluarQuery)
            ->selectRaw('MONTH(buku_keluar.tanggal_keluar) as bulan, COUNT(DISTINCT buku_keluar.id) as trx_count, SUM(detail_buku_keluar.jumlah) as total_qty')
            ->groupBy('bulan')
            ->get();

        foreach ($keluarPerBulanSummary as $item) {
            $b = (int) $item->bulan;
            if (isset($monthDataCounts[$b])) {
                $monthDataCounts[$b]['keluar_count'] = (int) $item->trx_count;
                $monthDataCounts[$b]['keluar_qty'] = (int) $item->total_qty;
                $monthDataCounts[$b]['total_trx'] += (int) $item->trx_count;
            }
        }

        // 3. Base Query Buku Masuk & Buku Keluar (Filtered)
        $masukQuery = DB::table('buku_masuk')
            ->join('detail_buku_masuk', 'buku_masuk.id', '=', 'detail_buku_masuk.id_buku_masuk')
            ->join('buku', 'detail_buku_masuk.id_buku', '=', 'buku.id');

        $keluarQuery = DB::table('buku_keluar')
            ->join('detail_buku_keluar', 'buku_keluar.id', '=', 'detail_buku_keluar.id_buku_keluar')
            ->join('buku', 'detail_buku_keluar.id_buku', '=', 'buku.id');

        // Terapkan Filter Kategori
        if ($selectedKategori && $selectedKategori !== 'all') {
            $masukQuery->where('buku.id_kategori', $selectedKategori);
            $keluarQuery->where('buku.id_kategori', $selectedKategori);
        }

        // Terapkan Filter Tanggal Spesifik (jika kalender dipilih)
        if ($selectedTanggal && $selectedTanggal !== 'all') {
            $masukQuery->whereDate('buku_masuk.tanggal_masuk', $selectedTanggal);
            $keluarQuery->whereDate('buku_keluar.tanggal_keluar', $selectedTanggal);
        } else {
            // Terapkan Filter Tahun
            if ($selectedTahun && $selectedTahun !== 'all') {
                $masukQuery->whereYear('buku_masuk.tanggal_masuk', $selectedTahun);
                $keluarQuery->whereYear('buku_keluar.tanggal_keluar', $selectedTahun);
            }

            // Terapkan Filter Bulan
            if ($selectedBulan && $selectedBulan !== 'all') {
                $masukQuery->whereMonth('buku_masuk.tanggal_masuk', $selectedBulan);
                $keluarQuery->whereMonth('buku_keluar.tanggal_keluar', $selectedBulan);
            }
        }

        // 4. Hitung Kartu Statistik Riil
        $totalMasukTrx = (clone $masukQuery)->distinct('buku_masuk.id')->count('buku_masuk.id');
        $totalKeluarTrx = (clone $keluarQuery)->distinct('buku_keluar.id')->count('buku_keluar.id');
        $totalTransaksi = $totalMasukTrx + $totalKeluarTrx;

        // Total Volume Masuk & Keluar (unit)
        $totalUnitMasuk = (int) (clone $masukQuery)->sum('detail_buku_masuk.jumlah');
        $totalUnitKeluar = (int) (clone $keluarQuery)->sum('detail_buku_keluar.jumlah');
        $netMovement = $totalUnitMasuk - $totalUnitKeluar;

        // Buku Terlaris (buku dengan kuantitas keluar terbanyak sesuai filter)
        $bukuTerlaris = (clone $keluarQuery)
            ->select('buku.id', 'buku.judul', DB::raw('SUM(detail_buku_keluar.jumlah) as total_keluar'))
            ->groupBy('buku.id', 'buku.judul')
            ->orderByDesc('total_keluar')
            ->first();

        $bukuTerlarisJudul = $bukuTerlaris->judul ?? 'Belum ada transaksi';
        $bukuTerlarisQty = (int) ($bukuTerlaris->total_keluar ?? 0);

        // Efisiensi Rak
        $totalKapasitasRak = (int) RakBuku::sum('kapasitas_max');
        $totalStokFisik = (int) StokBuku::sum('stok_sisa');
        $efisiensiRak = $totalKapasitasRak > 0 ? round(($totalStokFisik / $totalKapasitasRak) * 100, 1) : 0;

        // 5. Data Bar Chart (12 Bulan untuk tahun yang dipilih)
        $chartMasukQuery = DB::table('buku_masuk')
            ->join('detail_buku_masuk', 'buku_masuk.id', '=', 'detail_buku_masuk.id_buku_masuk')
            ->join('buku', 'detail_buku_masuk.id_buku', '=', 'buku.id');

        $chartKeluarQuery = DB::table('buku_keluar')
            ->join('detail_buku_keluar', 'buku_keluar.id', '=', 'detail_buku_keluar.id_buku_keluar')
            ->join('buku', 'detail_buku_keluar.id_buku', '=', 'buku.id');

        if ($selectedKategori && $selectedKategori !== 'all') {
            $chartMasukQuery->where('buku.id_kategori', $selectedKategori);
            $chartKeluarQuery->where('buku.id_kategori', $selectedKategori);
        }

        if ($selectedTahun && $selectedTahun !== 'all') {
            $chartMasukQuery->whereYear('buku_masuk.tanggal_masuk', $selectedTahun);
            $chartKeluarQuery->whereYear('buku_keluar.tanggal_keluar', $selectedTahun);
        }

        $masukPerBulan = (clone $chartMasukQuery)
            ->selectRaw('MONTH(buku_masuk.tanggal_masuk) as bulan, SUM(detail_buku_masuk.jumlah) as total')
            ->groupBy('bulan')
            ->pluck('total', 'bulan')
            ->toArray();

        $keluarPerBulan = (clone $chartKeluarQuery)
            ->selectRaw('MONTH(buku_keluar.tanggal_keluar) as bulan, SUM(detail_buku_keluar.jumlah) as total')
            ->groupBy('bulan')
            ->pluck('total', 'bulan')
            ->toArray();

        $monthlyLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
        $monthlyMasuk = [];
        $monthlyKeluar = [];

        for ($m = 1; $m <= 12; $m++) {
            $monthlyMasuk[] = (int) ($masukPerBulan[$m] ?? 0);
            $monthlyKeluar[] = (int) ($keluarPerBulan[$m] ?? 0);
        }

        // 6. Data Donut Chart (Distribusi Kategori)
        $kategoriDistributionQuery = DB::table('kategori_buku')
            ->leftJoin('buku', 'kategori_buku.id', '=', 'buku.id_kategori')
            ->leftJoin('stok_buku', 'buku.id', '=', 'stok_buku.id_buku');

        if ($selectedKategori && $selectedKategori !== 'all') {
            $kategoriDistributionQuery->where('kategori_buku.id', $selectedKategori);
        }

        $kategoriDistribution = $kategoriDistributionQuery
            ->select(
                'kategori_buku.id',
                'kategori_buku.nama_kategori',
                DB::raw('COALESCE(SUM(stok_buku.stok_sisa), 0) as total_stok'),
                DB::raw('COUNT(DISTINCT buku.id) as total_judul')
            )
            ->groupBy('kategori_buku.id', 'kategori_buku.nama_kategori')
            ->orderByDesc('total_stok')
            ->get();

        $donutLabels = [];
        $donutData = [];
        $donutColors = [
            '#2d4636', '#4a6b54', '#6c9a77', '#81c784', 
            '#a3d9a5', '#c8e6c9', '#dce775', '#ffd54f'
        ];

        $totalStokDonut = (int) $kategoriDistribution->sum('total_stok');
        $totalJudulDonut = (int) $kategoriDistribution->sum('total_judul');

        foreach ($kategoriDistribution as $item) {
            $donutLabels[] = $item->nama_kategori;
            $donutData[] = (int) $item->total_stok;
        }

        // 7. Ringkasan Laporan Bulanan (12 Periode Tercatat)
        $monthlySummary = [];
        $thnLabel = ($selectedTahun && $selectedTahun !== 'all') ? ' ' . $selectedTahun : '';
        for ($m = 1; $m <= 12; $m++) {
            $mIn = (int) ($masukPerBulan[$m] ?? 0);
            $mOut = (int) ($keluarPerBulan[$m] ?? 0);
            $trxCount = $monthDataCounts[$m]['total_trx'] ?? 0;
            
            $monthlySummary[] = [
                'bulan_angka' => $m,
                'bulan' => $namaBulanLengkap[$m] . $thnLabel,
                'masuk' => $mIn,
                'keluar' => $mOut,
                'selisih' => $mIn - $mOut,
                'total_trx' => $trxCount,
                'status' => 'Selesai'
            ];
        }

        // 8. Laporan Asli: Transaksi Riil Detail dari Database
        $laporanMasukQuery = DB::table('buku_masuk')
            ->join('detail_buku_masuk', 'buku_masuk.id', '=', 'detail_buku_masuk.id_buku_masuk')
            ->join('buku', 'detail_buku_masuk.id_buku', '=', 'buku.id')
            ->leftJoin('kategori_buku', 'buku.id_kategori', '=', 'kategori_buku.id')
            ->leftJoin('users', 'buku_masuk.id_user', '=', 'users.id')
            ->select(
                'buku_masuk.id as transaksi_id',
                'buku_masuk.no_transaksi',
                'buku_masuk.tanggal_masuk as tanggal',
                'buku_masuk.keterangan as info_tambahan',
                'buku_masuk.status',
                'buku.judul',
                'buku.isbn',
                'kategori_buku.nama_kategori',
                'detail_buku_masuk.jumlah',
                'detail_buku_masuk.harga_satuan',
                'detail_buku_masuk.subtotal',
                'users.nama as user_name',
                DB::raw("'masuk' as tipe")
            );

        $laporanKeluarQuery = DB::table('buku_keluar')
            ->join('detail_buku_keluar', 'buku_keluar.id', '=', 'detail_buku_keluar.id_buku_keluar')
            ->join('buku', 'detail_buku_keluar.id_buku', '=', 'buku.id')
            ->leftJoin('kategori_buku', 'buku.id_kategori', '=', 'kategori_buku.id')
            ->leftJoin('users', 'buku_keluar.id_user', '=', 'users.id')
            ->select(
                'buku_keluar.id as transaksi_id',
                'buku_keluar.no_transaksi',
                'buku_keluar.tanggal_keluar as tanggal',
                DB::raw("CONCAT(COALESCE(buku_keluar.tujuan, '-'), CASE WHEN buku_keluar.expedisi IS NOT NULL AND buku_keluar.expedisi != '' THEN CONCAT(' (', buku_keluar.expedisi, ')') ELSE '' END) as info_tambahan"),
                'buku_keluar.status',
                'buku.judul',
                'buku.isbn',
                'kategori_buku.nama_kategori',
                'detail_buku_keluar.jumlah',
                'detail_buku_keluar.harga_satuan',
                'detail_buku_keluar.subtotal',
                'users.nama as user_name',
                DB::raw("'keluar' as tipe")
            );

        // Terapkan filter Kategori ke query laporan transaksi detail
        if ($selectedKategori && $selectedKategori !== 'all') {
            $laporanMasukQuery->where('buku.id_kategori', $selectedKategori);
            $laporanKeluarQuery->where('buku.id_kategori', $selectedKategori);
        }

        // Terapkan filter Tanggal ke query laporan transaksi detail jika ada
        if ($selectedTanggal && $selectedTanggal !== 'all') {
            $laporanMasukQuery->whereDate('buku_masuk.tanggal_masuk', $selectedTanggal);
            $laporanKeluarQuery->whereDate('buku_keluar.tanggal_keluar', $selectedTanggal);
        } else {
            // Terapkan filter Tahun ke query laporan transaksi detail
            if ($selectedTahun && $selectedTahun !== 'all') {
                $laporanMasukQuery->whereYear('buku_masuk.tanggal_masuk', $selectedTahun);
                $laporanKeluarQuery->whereYear('buku_keluar.tanggal_keluar', $selectedTahun);
            }

            // Terapkan filter Bulan ke query laporan transaksi detail
            if ($selectedBulan && $selectedBulan !== 'all') {
                $laporanMasukQuery->whereMonth('buku_masuk.tanggal_masuk', $selectedBulan);
                $laporanKeluarQuery->whereMonth('buku_keluar.tanggal_keluar', $selectedBulan);
            }
        }

        $transaksiMasukDetail = $laporanMasukQuery->orderBy('buku_masuk.tanggal_masuk', 'desc')->get();
        $transaksiKeluarDetail = $laporanKeluarQuery->orderBy('buku_keluar.tanggal_keluar', 'desc')->get();

        // Gabungkan seluruh transaksi riil dan urutkan berdasarkan tanggal terbaru
        $semuaTransaksiDetail = $transaksiMasukDetail->concat($transaksiKeluarDetail)->sortByDesc('tanggal')->values();

        // Total nominal mutasi
        $totalNilaiMasuk = $transaksiMasukDetail->sum('subtotal');
        $totalNilaiKeluar = $transaksiKeluarDetail->sum('subtotal');

        return compact(
            'kategoriList',
            'availableYears',
            'selectedKategori',
            'selectedTahun',
            'selectedBulan',
            'selectedTanggal',
            'namaBulanLengkap',
            'monthDataCounts',
            'totalTransaksi',
            'totalMasukTrx',
            'totalKeluarTrx',
            'totalUnitMasuk',
            'totalUnitKeluar',
            'netMovement',
            'bukuTerlarisJudul',
            'bukuTerlarisQty',
            'efisiensiRak',
            'totalKapasitasRak',
            'totalStokFisik',
            'monthlyLabels',
            'monthlyMasuk',
            'monthlyKeluar',
            'kategoriDistribution',
            'donutLabels',
            'donutData',
            'donutColors',
            'totalStokDonut',
            'totalJudulDonut',
            'monthlySummary',
            'semuaTransaksiDetail',
            'transaksiMasukDetail',
            'transaksiKeluarDetail',
            'totalNilaiMasuk',
            'totalNilaiKeluar'
        );
    }

    public function index(Request $request)
    {
        $data = $this->getLaporanData($request);
        return view('laporan.index', $data);
    }

    public function exportPdf(Request $request)
    {
        $data = $this->getLaporanData($request);

        // Kategori Terpilih
        $kategoriObj = ($data['selectedKategori'] && $data['selectedKategori'] !== 'all') 
            ? KategoriBuku::find($data['selectedKategori']) 
            : null;
        $data['namaKategoriTerpilih'] = $kategoriObj ? $kategoriObj->nama_kategori : 'Semua Kategori';

        // Periode Deskriptif
        if ($data['selectedTanggal'] && $data['selectedTanggal'] !== 'all') {
            $data['periodeLabel'] = Carbon::parse($data['selectedTanggal'])->translatedFormat('d F Y');
        } elseif ($data['selectedBulan'] && $data['selectedBulan'] !== 'all') {
            $namaBulan = $data['namaBulanLengkap'][(int)$data['selectedBulan']] ?? '';
            $thn = ($data['selectedTahun'] && $data['selectedTahun'] !== 'all') ? ' ' . $data['selectedTahun'] : '';
            $data['periodeLabel'] = $namaBulan . $thn;
        } elseif ($data['selectedTahun'] && $data['selectedTahun'] !== 'all') {
            $data['periodeLabel'] = 'Tahun ' . $data['selectedTahun'];
        } else {
            $data['periodeLabel'] = 'Semua Periode (Data Lengkap)';
        }

        $user = Auth::user();
        $data['tanggalCetak'] = Carbon::now()->timezone('Asia/Jakarta')->translatedFormat('d F Y, H:i') . ' WIB';
        $data['petugasCetak'] = $user->nama ?? $user->name ?? 'Admin Cahaya Buku';
        $data['roleCetak'] = strtoupper($user->role ?? 'ADMIN');

        $pdf = Pdf::loadView('laporan.pdf', $data);
        $pdf->setPaper('a4', 'portrait');

        $safePeriode = preg_replace('/[^a-zA-Z0-9_\-]/', '_', $data['periodeLabel']);
        $filename = "Laporan_Inventaris_Cahaya_Buku_{$safePeriode}.pdf";

        return $pdf->download($filename);
    }
}

