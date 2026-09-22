<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StokBuku;
use App\Models\BukuMasuk;
use App\Models\BukuKeluar;
use Carbon\Carbon;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $notifications = [];
        $unreadCount = 0;
        $readIds = session('read_notifications', []);

        // 1. Aktivitas Perubahan Sistem & Operasional Terkini (Real-time Log)
        $recentAktivitas = \App\Models\Aktivitas::with('user')
            ->orderBy('id', 'desc')
            ->take(20)
            ->get();

        foreach ($recentAktivitas as $akt) {
            $notifId = 'akt-' . $akt->id;
            $isRead = in_array($notifId, $readIds);
            if (!$isRead) {
                $unreadCount++;
            }

            // Tentukan tipe & badge berdasarkan jenis aksi
            $aksiLower = strtolower($akt->aksi);
            $type = 'info';
            if (str_contains($aksiLower, 'hapus') || str_contains($aksiLower, 'batal') || str_contains($aksiLower, 'nonaktif')) {
                $type = 'danger';
            } elseif (str_contains($aksiLower, 'ubah') || str_contains($aksiLower, 'update') || str_contains($aksiLower, 'penyesuaian')) {
                $type = 'warning';
            } elseif (str_contains($aksiLower, 'tambah') || str_contains($aksiLower, 'selesai') || str_contains($aksiLower, 'diterima') || str_contains($aksiLower, 'backup') || str_contains($aksiLower, 'pemulihan')) {
                $type = 'success';
            }

            // Tentukan rute navigasi modul terkait
            $url = route('dashboard');
            if (str_contains($aksiLower, 'buku masuk')) {
                $url = route('buku-masuk.index');
            } elseif (str_contains($aksiLower, 'buku keluar')) {
                $url = route('buku-keluar.index');
            } elseif (str_contains($aksiLower, 'stok')) {
                $url = route('stok.index');
            } elseif (str_contains($aksiLower, 'buku')) {
                $url = route('buku.index');
            } elseif (str_contains($aksiLower, 'kategori')) {
                $url = route('kategori.index');
            } elseif (str_contains($aksiLower, 'penulis')) {
                $url = route('penulis.index');
            } elseif (str_contains($aksiLower, 'penerbit')) {
                $url = route('penerbit.index');
            } elseif (str_contains($aksiLower, 'rak')) {
                $url = route('rak.index');
            } elseif (str_contains($aksiLower, 'backup') || str_contains($aksiLower, 'pemulihan') || str_contains($aksiLower, '2fa') || str_contains($aksiLower, 'preferensi')) {
                $url = route('pengaturan.index');
            } elseif (str_contains($aksiLower, 'kata sandi') || str_contains($aksiLower, 'hak akses')) {
                $url = route('pengguna.index');
            }

            $carbonTime = $akt->created_at ? Carbon::parse($akt->created_at) : Carbon::now();

            $notifications[] = [
                'id' => $notifId,
                'type' => $type,
                'category' => 'Aktivitas',
                'title' => $akt->aksi,
                'message' => $akt->detail,
                'url' => $url,
                'time' => $carbonTime->diffForHumans(),
                'timestamp' => $carbonTime->timestamp,
                'is_read' => $isRead,
            ];
        }

        // 2. Alert Stok Menipis / Habis (Kritis)
        $lowStocks = StokBuku::with(['buku'])
            ->where(function($q) {
                $q->whereColumn('stok_sisa', '<=', 'min_stok')
                  ->orWhere('stok_sisa', '<=', 5);
            })
            ->take(5)
            ->get();

        foreach ($lowStocks as $stok) {
            $bukuJudul = $stok->buku ? $stok->buku->judul : 'Buku #' . $stok->id_buku;
            $notifId = 'stok-' . $stok->id . '-' . $stok->stok_sisa;
            $isRead = in_array($notifId, $readIds);
            if (!$isRead) {
                $unreadCount++;
            }

            $isHabis = $stok->stok_sisa <= 0;
            $notifications[] = [
                'id' => $notifId,
                'type' => $isHabis ? 'danger' : 'warning',
                'category' => 'Stok Inventaris',
                'title' => $isHabis ? 'Stok Buku Habis' : 'Stok Buku Menipis',
                'message' => "Buku \"{$bukuJudul}\" tersisa {$stok->stok_sisa} unit (Min: {$stok->min_stok})",
                'url' => route('stok.index'),
                'time' => $isHabis ? 'Kritis' : 'Perlu Restock',
                'timestamp' => time() + 1000, // Tingkatkan prioritas visual
                'is_read' => $isRead,
            ];
        }

        // 3. Alert Transaksi Buku Masuk Pending / Belum Selesai
        $pendingMasuk = BukuMasuk::with(['details.buku'])
            ->where('status', '!=', 'Selesai')
            ->orderBy('id', 'desc')
            ->take(4)
            ->get();

        foreach ($pendingMasuk as $bm) {
            $notifId = 'bm-' . $bm->id . '-' . $bm->status;
            $isRead = in_array($notifId, $readIds);
            if (!$isRead) {
                $unreadCount++;
            }

            $totalItem = $bm->details->sum('jumlah');
            $noTrx = ltrim($bm->no_transaksi, '#');
            $bmTime = $bm->tanggal_masuk ? Carbon::parse($bm->tanggal_masuk) : Carbon::now();

            $notifications[] = [
                'id' => $notifId,
                'type' => 'info',
                'category' => 'Buku Masuk',
                'title' => 'Verifikasi Buku Masuk',
                'message' => "Transaksi #{$noTrx} status '{$bm->status}' ({$totalItem} unit)",
                'url' => route('buku-masuk.index'),
                'time' => $bm->tanggal_masuk ? $bmTime->diffForHumans() : 'Perlu diperiksa',
                'timestamp' => $bmTime->timestamp,
                'is_read' => $isRead,
            ];
        }

        // 4. Transaksi Buku Masuk Terbaru yang Selesai
        $recentMasuk = BukuMasuk::where('status', 'Selesai')
            ->orderBy('id', 'desc')
            ->take(3)
            ->get();

        foreach ($recentMasuk as $rm) {
            $notifId = 'bm-done-' . $rm->id;
            $isRead = in_array($notifId, $readIds);
            if (!$isRead) {
                $unreadCount++;
            }

            $noTrxSelesai = ltrim($rm->no_transaksi, '#');
            $rmTime = $rm->tanggal_masuk ? Carbon::parse($rm->tanggal_masuk) : Carbon::now();

            $notifications[] = [
                'id' => $notifId,
                'type' => 'success',
                'category' => 'Transaksi Sukses',
                'title' => 'Buku Masuk Diterima',
                'message' => "Transaksi #{$noTrxSelesai} telah selesai diverifikasi.",
                'url' => route('buku-masuk.index'),
                'time' => $rm->tanggal_masuk ? $rmTime->diffForHumans() : 'Selesai',
                'timestamp' => $rmTime->timestamp,
                'is_read' => $isRead,
            ];
        }

        // Urutkan notifikasi: yang belum dibaca diprioritaskan, kemudian urutkan berdasarkan timestamp terbaru
        usort($notifications, function ($a, $b) {
            if ($a['is_read'] !== $b['is_read']) {
                return $a['is_read'] ? 1 : -1;
            }
            return ($b['timestamp'] ?? 0) <=> ($a['timestamp'] ?? 0);
        });

        return response()->json([
            'unread_count' => $unreadCount,
            'total' => count($notifications),
            'notifications' => $notifications
        ]);
    }

    public function markReadAll(Request $request)
    {
        $allIds = $request->input('ids', []);
        $existing = session('read_notifications', []);
        session(['read_notifications' => array_unique(array_merge($existing, $allIds))]);

        return response()->json([
            'success' => true,
            'message' => 'Semua notifikasi telah ditandai dibaca.'
        ]);
    }
}
