<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Aktivitas;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;

class PenggunaController extends Controller
{
    /**
     * Konfigurasi modul hak akses default sesuai skema tampilan awal
     */
    protected function defaultHakAkses(): array
    {
        return [
            'dashboard' => [
                'nama' => 'Dashboard Visualisasi',
                'deskripsi_aktif' => 'Akses penuh ke metrik dan statistik gudang.',
                'deskripsi_nonaktif' => 'Dibatasi. Hanya dapat melihat ringkasan terbatas.',
                'status' => true,
            ],
            'buku' => [
                'nama' => 'Manajemen Data Buku',
                'deskripsi_aktif' => 'Tambah, edit, dan kelola katalog buku.',
                'deskripsi_nonaktif' => 'Dibatasi. Hanya dapat melihat daftar buku (Read-only).',
                'status' => true,
            ],
            'inventori' => [
                'nama' => 'Kontrol Inventori (Masuk/Keluar)',
                'deskripsi_aktif' => 'Proses penerimaan dan pengeluaran stok fisik.',
                'deskripsi_nonaktif' => 'Dibatasi. Tidak dapat melakukan mutasi stok fisik.',
                'status' => true,
            ],
            'master' => [
                'nama' => 'Manajemen Master Data',
                'deskripsi_aktif' => 'Akses ke data Kategori, Penulis, Penerbit, Rak.',
                'deskripsi_nonaktif' => 'Dibatasi. Perubahan master data dinonaktifkan.',
                'status' => true,
            ],
            'pengaturan' => [
                'nama' => 'Pengaturan Sistem',
                'deskripsi_aktif' => 'Akses penuh ke konfigurasi sistem inventaris.',
                'deskripsi_nonaktif' => 'Dibatasi. Membutuhkan eskalasi ke Admin IT.',
                'status' => false,
            ],
            'hapus_permanen' => [
                'nama' => 'Hapus Permanen Data',
                'deskripsi_aktif' => 'Diizinkan melakukan penghapusan data secara permanen.',
                'deskripsi_nonaktif' => 'Dibatasi. Hanya dapat melakukan soft-delete.',
                'status' => false,
            ],
        ];
    }

    /**
     * Mengambil status hak akses tersimpan atau default
     */
    protected function getHakAkses(): array
    {
        $defaults = $this->defaultHakAkses();
        $filePath = storage_path('app/hak_akses.json');

        if (File::exists($filePath)) {
            $saved = json_decode(File::get($filePath), true);
            if (is_array($saved)) {
                foreach ($defaults as $key => $item) {
                    if (isset($saved[$key]) && is_bool($saved[$key])) {
                        $defaults[$key]['status'] = $saved[$key];
                    }
                }
            }
        }

        return $defaults;
    }

    public function index()
    {
        $user = Auth::user();
        $aktivitas = Aktivitas::orderBy('created_at', 'desc')->get();
        $hakAkses = $this->getHakAkses();

        // Cari riwayat log pembaruan kata sandi terakhir
        $lastPasswordUpdateLog = Aktivitas::where('aksi', 'Ubah Kata Sandi')
            ->orderBy('created_at', 'desc')
            ->first();

        $lastPasswordUpdate = $lastPasswordUpdateLog
            ? \Carbon\Carbon::parse($lastPasswordUpdateLog->created_at)->timezone(config('app.timezone', 'Asia/Jakarta'))->translatedFormat('d M Y')
            : '01 Mar 2024';

        $accountCreatedAt = ($user && $user->created_at)
            ? \Carbon\Carbon::parse($user->created_at)->timezone(config('app.timezone', 'Asia/Jakarta'))->translatedFormat('d M Y')
            : '12 Okt 2023';

        return view('pengguna.index', compact('user', 'aktivitas', 'hakAkses', 'lastPasswordUpdate', 'accountCreatedAt'));
    }

    /**
     * Proses ubah kata sandi akses akun bersama
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'nullable|string',
            'password' => 'required|string|min:6|confirmed',
        ], [
            'password.required' => 'Kata sandi baru wajib diisi.',
            'password.min' => 'Kata sandi baru minimal harus 6 karakter.',
            'password.confirmed' => 'Konfirmasi kata sandi baru tidak sesuai.',
        ]);

        /** @var User $user */
        $user = Auth::user();

        // Validasi kata sandi saat ini jika diisi
        if ($request->filled('current_password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'Kata sandi saat ini tidak sesuai.'])->withInput();
            }
        }

        // Update kata sandi ke database
        $user->password = Hash::make($request->password);
        $user->save();

        // Catat ke log aktivitas keamanan
        Aktivitas::create([
            'id_user' => $user->id,
            'aksi' => 'Ubah Kata Sandi',
            'detail' => 'Kata sandi akses akun bersama petugas_cahaya berhasil diperbarui',
            'terminal' => 'TERM-01',
            'ip_address' => $request->ip() ?? '127.0.0.1',
            'created_at' => now(),
        ]);

        return back()->with('success', 'Kata sandi akses akun bersama berhasil diperbarui!');
    }

    /**
     * Proses simpan konfigurasi hak akses modul
     */
    public function updateHakAkses(Request $request)
    {
        $defaults = $this->defaultHakAkses();
        $saved = [];

        foreach (array_keys($defaults) as $key) {
            $saved[$key] = $request->boolean('hak_akses.' . $key);
        }

        $filePath = storage_path('app/hak_akses.json');
        File::put($filePath, json_encode($saved, JSON_PRETTY_PRINT));

        /** @var User $user */
        $user = Auth::user();

        // Hitung modul aktif
        $aktifCount = count(array_filter($saved));

        // Catat ke log aktivitas keamanan
        Aktivitas::create([
            'id_user' => $user ? $user->id : 1,
            'aksi' => 'Update Hak Akses',
            'detail' => "Hak akses modul operasional diperbarui ({$aktifCount} aktif, " . (count($defaults) - $aktifCount) . " dibatasi)",
            'terminal' => 'TERM-01',
            'ip_address' => $request->ip() ?? '127.0.0.1',
            'created_at' => now(),
        ]);

        return back()->with('success', 'Konfigurasi hak akses modul berhasil diperbarui!');
    }
}
