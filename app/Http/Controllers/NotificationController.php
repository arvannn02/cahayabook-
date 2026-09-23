<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Aktivitas;
use Carbon\Carbon;

class NotificationController extends Controller
{
    /**
     * Mengambil daftar notifikasi berbasis aktivitas riil sistem
     */
    public function index(Request $request)
    {
        $notifications = [];
        $unreadCount = 0;

        // Ambil ID aktivitas tertinggi saat ini di database
        $latestActivityId = Aktivitas::max('id') ?? 0;

        // Inisialisasi last_read_activity_id jika belum ada di sesi:
        // Saat pengguna pertama kali membuka sistem / login, aktivitas lampau sebelum sesi ini
        // ditandai sebagai riwayat (terbaca) agar tidak membombardir puluhan notifikasi lama.
        if (!session()->has('last_read_activity_id')) {
            session(['last_read_activity_id' => $latestActivityId]);
        }

        $lastReadId = (int) session('last_read_activity_id', $latestActivityId);
        $readIds = session('read_notifications', []);

        // Ambil aktivitas riil sistem dari tabel aktivitas (sesuai database awal)
        $recentAktivitas = Aktivitas::with('user')
            ->orderBy('id', 'desc')
            ->take(12)
            ->get();

        foreach ($recentAktivitas as $akt) {
            $notifId = 'akt-' . $akt->id;
            $isRead = ($akt->id <= $lastReadId) || in_array($notifId, $readIds);

            if (!$isRead) {
                $unreadCount++;
            }

            // Klasifikasi visual notifikasi berdasarkan aksi sistem
            $aksiLower = strtolower($akt->aksi);
            $type = 'info';

            if (str_contains($aksiLower, 'hapus') || str_contains($aksiLower, 'batal') || str_contains($aksiLower, 'nonaktif')) {
                $type = 'danger';
            } elseif (str_contains($aksiLower, 'ubah') || str_contains($aksiLower, 'update') || str_contains($aksiLower, 'penyesuaian') || str_contains($aksiLower, 'keluar')) {
                $type = 'warning';
            } elseif (str_contains($aksiLower, 'tambah') || str_contains($aksiLower, 'masuk') || str_contains($aksiLower, 'selesai') || str_contains($aksiLower, 'backup') || str_contains($aksiLower, 'pemulihan')) {
                $type = 'success';
            }

            // Kategori notifikasi untuk tab filter (Aktivitas, Stok Inventaris, Buku Masuk / Transaksi)
            if (str_contains($aksiLower, 'masuk') || str_contains($aksiLower, 'keluar') || str_contains($aksiLower, 'transaksi')) {
                $category = str_contains($aksiLower, 'keluar') ? 'Buku Keluar' : 'Buku Masuk';
            } elseif (str_contains($aksiLower, 'stok')) {
                $category = 'Stok Inventaris';
            } else {
                $category = 'Aktivitas';
            }

            // Tentukan tautan rute navigasi modul terkait
            $url = route('dashboard');
            if (str_contains($aksiLower, 'buku masuk')) {
                $url = route('buku-masuk.index');
            } elseif (str_contains($aksiLower, 'buku keluar')) {
                $url = route('buku-keluar.index');
            } elseif (str_contains($aksiLower, 'stok')) {
                $url = route('stok.index');
            } elseif (str_contains($aksiLower, 'kategori')) {
                $url = route('kategori.index');
            } elseif (str_contains($aksiLower, 'penulis')) {
                $url = route('penulis.index');
            } elseif (str_contains($aksiLower, 'penerbit')) {
                $url = route('penerbit.index');
            } elseif (str_contains($aksiLower, 'rak')) {
                $url = route('rak.index');
            } elseif (str_contains($aksiLower, 'buku')) {
                $url = route('buku.index');
            } elseif (str_contains($aksiLower, 'backup') || str_contains($aksiLower, 'pemulihan') || str_contains($aksiLower, '2fa') || str_contains($aksiLower, 'preferensi')) {
                $url = route('pengaturan.index');
            } elseif (str_contains($aksiLower, 'kata sandi') || str_contains($aksiLower, 'pengguna')) {
                $url = route('pengguna.index');
            }

            $carbonTime = $akt->created_at ? Carbon::parse($akt->created_at) : Carbon::now();

            $notifications[] = [
                'id' => $notifId,
                'type' => $type,
                'category' => $category,
                'title' => $akt->aksi,
                'message' => $akt->detail,
                'url' => $url,
                'time' => $carbonTime->diffForHumans(),
                'timestamp' => $carbonTime->timestamp,
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

    /**
     * Menandai seluruh notifikasi telah dibaca
     */
    public function markReadAll(Request $request)
    {
        $latest = Aktivitas::max('id') ?? 0;
        session(['last_read_activity_id' => $latest]);

        $allIds = $request->input('ids', []);
        $existing = session('read_notifications', []);
        session(['read_notifications' => array_unique(array_merge($existing, $allIds))]);

        return response()->json([
            'success' => true,
            'message' => 'Semua notifikasi telah ditandai dibaca.'
        ]);
    }
}
