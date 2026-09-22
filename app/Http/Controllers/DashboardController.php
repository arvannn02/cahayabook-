<?php

namespace App\Http\Controllers;

use App\Models\Aktivitas;
use App\Models\Buku;
use App\Models\BukuKeluar;
use App\Models\BukuMasuk;
use App\Models\KategoriBuku;
use App\Models\StokBuku;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $yearsMasuk = DB::table('buku_masuk')
            ->selectRaw('DISTINCT YEAR(tanggal_masuk) as tahun')
            ->pluck('tahun');
        $yearsKeluar = DB::table('buku_keluar')
            ->selectRaw('DISTINCT YEAR(tanggal_keluar) as tahun')
            ->pluck('tahun');

        $availableYears = $yearsMasuk->concat($yearsKeluar)
            ->unique()
            ->filter()
            ->sortDesc()
            ->values()
            ->toArray();

        $selectedYear = $request->get('tahun', null);
        if (!$selectedYear && count($availableYears) > 0) {
            $selectedYear = (string) $availableYears[0];
        } elseif (!$selectedYear) {
            $selectedYear = date('Y');
        }

        $currentYear = $selectedYear;
        $startOfWeek = Carbon::now()->startOfWeek();

        // 1. Stat Cards
        $totalJudulBuku = Buku::count();
        $totalStokFisik = (int) StokBuku::sum('stok_sisa');
        $totalStokMasuk = (int) StokBuku::sum('stok_masuk');
        $totalStokKeluar = (int) StokBuku::sum('stok_keluar');
        
        // Buku baru ditambahkan minggu ini (dari buku_masuk)
        $bukuBaruMingguIni = (int) DB::table('buku_masuk')
            ->join('detail_buku_masuk', 'buku_masuk.id', '=', 'detail_buku_masuk.id_buku_masuk')
            ->where('buku_masuk.tanggal_masuk', '>=', $startOfWeek)
            ->sum('detail_buku_masuk.jumlah');

        // Persentase stok tersedia vs total masuk
        $persentaseTersedia = $totalStokMasuk > 0 
            ? round(($totalStokFisik / $totalStokMasuk) * 100, 1) 
            : 100;

        // 2. Data Statistik Bulanan (Volume: Masuk vs Keluar)
        $masukQuery = DB::table('buku_masuk')
            ->join('detail_buku_masuk', 'buku_masuk.id', '=', 'detail_buku_masuk.id_buku_masuk');

        $keluarQuery = DB::table('buku_keluar')
            ->join('detail_buku_keluar', 'buku_keluar.id', '=', 'detail_buku_keluar.id_buku_keluar');

        if ($selectedYear !== 'all') {
            $masukQuery->whereYear('buku_masuk.tanggal_masuk', $selectedYear);
            $keluarQuery->whereYear('buku_keluar.tanggal_keluar', $selectedYear);
        }

        $masukRaw = $masukQuery
            ->selectRaw('MONTH(buku_masuk.tanggal_masuk) as bulan, SUM(detail_buku_masuk.jumlah) as total')
            ->groupBy('bulan')
            ->pluck('total', 'bulan')
            ->toArray();

        $keluarRaw = $keluarQuery
            ->selectRaw('MONTH(buku_keluar.tanggal_keluar) as bulan, SUM(detail_buku_keluar.jumlah) as total')
            ->groupBy('bulan')
            ->pluck('total', 'bulan')
            ->toArray();

        $monthlyLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
        $monthlyMasuk = [];
        $monthlyKeluar = [];

        for ($m = 1; $m <= 12; $m++) {
            $monthlyMasuk[] = (int) ($masukRaw[$m] ?? 0);
            $monthlyKeluar[] = (int) ($keluarRaw[$m] ?? 0);
        }

        // 3. Distribusi Kategori (Donut Chart & Kategori View)
        $kategoriList = DB::table('kategori_buku')
            ->leftJoin('buku', 'kategori_buku.id', '=', 'buku.id_kategori')
            ->leftJoin('stok_buku', 'buku.id', '=', 'stok_buku.id_buku')
            ->select('kategori_buku.id', 'kategori_buku.nama_kategori', DB::raw('COALESCE(SUM(stok_buku.stok_sisa), 0) as total_stok'))
            ->groupBy('kategori_buku.id', 'kategori_buku.nama_kategori')
            ->orderByDesc('total_stok')
            ->get();

        $kategoriLabels = [];
        $kategoriStok = [];
        $kategoriColors = [
            '#355240', '#4a6b54', '#6c9a77', '#a3d9a5', '#c8e6c9', 
            '#81c784', '#aed581', '#dce775', '#ffd54f', '#ffb74d'
        ];

        foreach ($kategoriList as $k) {
            $kategoriLabels[] = $k->nama_kategori;
            $kategoriStok[] = (int) $k->total_stok;
        }

        $kategoriDominanPersen = $totalStokFisik > 0 && count($kategoriStok) > 0 
            ? round(($kategoriStok[0] / $totalStokFisik) * 100) 
            : 0;
        $kategoriDominanNama = $kategoriLabels[0] ?? 'Kategori';

        // 4. Aktivitas Terkini (5 transaksi / log terbaru)
        $aktivitasList = Aktivitas::with('user')
            ->orderByDesc('created_at')
            ->take(5)
            ->get();

        // Fallback transaksi buku masuk & keluar jika log aktivitas belum banyak
        $recentTransactions = collect();
        if ($aktivitasList->count() < 5) {
            $recentMasuk = BukuMasuk::with(['details.buku', 'user'])
                ->orderByDesc('tanggal_masuk')
                ->take(3)
                ->get()
                ->map(function ($item) {
                    $firstBook = $item->details->first()?->buku?->judul ?? 'Buku Masuk';
                    $totalQty = $item->details->sum('jumlah');
                    return [
                        'type' => 'masuk',
                        'title' => "Buku Masuk: \"{$firstBook}\" ({$totalQty} unit)",
                        'subtitle' => 'Oleh: ' . ($item->user->nama ?? 'Admin') . ($item->keterangan ? ' - ' . $item->keterangan : ''),
                        'time' => Carbon::parse($item->tanggal_masuk)->diffForHumans(),
                    ];
                });

            $recentKeluar = BukuKeluar::with(['details.buku', 'user'])
                ->orderByDesc('tanggal_keluar')
                ->take(3)
                ->get()
                ->map(function ($item) {
                    $firstBook = $item->details->first()?->buku?->judul ?? 'Buku Keluar';
                    $totalQty = $item->details->sum('jumlah');
                    return [
                        'type' => 'keluar',
                        'title' => "Buku Keluar: \"{$firstBook}\" ({$totalQty} unit)",
                        'subtitle' => 'Oleh: ' . ($item->user->nama ?? 'Admin') . ($item->tujuan ? ' - Tujuan: ' . $item->tujuan : ''),
                        'time' => Carbon::parse($item->tanggal_keluar)->diffForHumans(),
                    ];
                });

            $recentTransactions = $recentMasuk->concat($recentKeluar)->take(5);
        }

        return view('dashboard', compact(
            'currentYear',
            'totalJudulBuku',
            'totalStokFisik',
            'totalStokMasuk',
            'totalStokKeluar',
            'bukuBaruMingguIni',
            'persentaseTersedia',
            'monthlyLabels',
            'monthlyMasuk',
            'monthlyKeluar',
            'kategoriList',
            'kategoriLabels',
            'kategoriStok',
            'kategoriColors',
            'kategoriDominanPersen',
            'kategoriDominanNama',
            'aktivitasList',
            'recentTransactions',
            'availableYears',
            'selectedYear'
        ));
    }
}