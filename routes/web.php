<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\PenulisController;
use App\Http\Controllers\PenerbitController;
use App\Http\Controllers\RakBukuController;
use App\Http\Controllers\BukuMasukController;
use App\Http\Controllers\BukuKeluarController;
use App\Http\Controllers\StokController;
use App\Http\Controllers\DashboardController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'authenticate']);
    Route::get('/login/2fa', [AuthController::class, 'show2faChallenge'])->name('login.2fa');
    Route::post('/login/2fa', [AuthController::class, 'verify2faChallenge'])->name('login.2fa.verify');
    Route::post('/login/2fa/cancel', [AuthController::class, 'cancel2faChallenge'])->name('login.2fa.cancel');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Data Buku
    Route::get('/buku', [BukuController::class, 'index'])->name('buku.index');
    Route::post('/buku', [BukuController::class, 'store'])->name('buku.store');
    Route::put('/buku/{buku}', [BukuController::class, 'update'])->name('buku.update');
    Route::delete('/buku/{buku}', [BukuController::class, 'destroy'])->name('buku.destroy');

    // Kategori Buku
    Route::resource('kategori', KategoriController::class)->only(['index', 'store', 'update', 'destroy']);

    // Penulis
    Route::resource('penulis', PenulisController::class)->only(['index', 'store', 'update', 'destroy']);

    // Penerbit
    Route::resource('penerbit', PenerbitController::class)->only(['index', 'store', 'update', 'destroy']);

    // Rak Buku
    Route::resource('rak', RakBukuController::class)->only(['index', 'store', 'update', 'destroy']);

    // Buku Masuk
    Route::get('/buku-masuk', [BukuMasukController::class, 'index'])->name('buku-masuk.index');
    Route::post('/buku-masuk', [BukuMasukController::class, 'store'])->name('buku-masuk.store');
    Route::put('/buku-masuk/{bukuMasuk}', [BukuMasukController::class, 'update'])->name('buku-masuk.update');
    Route::delete('/buku-masuk/{bukuMasuk}', [BukuMasukController::class, 'destroy'])->name('buku-masuk.destroy');

    // Buku Keluar
    Route::get('/buku-keluar', [BukuKeluarController::class, 'index'])->name('buku-keluar.index');
    Route::post('/buku-keluar', [BukuKeluarController::class, 'store'])->name('buku-keluar.store');
    Route::put('/buku-keluar/{bukuKeluar}', [BukuKeluarController::class, 'update'])->name('buku-keluar.update');
    Route::delete('/buku-keluar/{bukuKeluar}', [BukuKeluarController::class, 'destroy'])->name('buku-keluar.destroy');

    // Stok Buku
    Route::get('/stok', [StokController::class, 'index'])->name('stok.index');
    Route::put('/stok/{stok}', [StokController::class, 'update'])->name('stok.update');
    Route::delete('/stok/{stok}', [StokController::class, 'destroy'])->name('stok.destroy');

    // Laporan
    Route::get('/laporan', [\App\Http\Controllers\LaporanController::class, 'index'])->name('laporan.index');

    // Pengguna
    Route::get('/pengguna', [\App\Http\Controllers\PenggunaController::class, 'index'])->name('pengguna.index');
    Route::post('/pengguna/update-password', [\App\Http\Controllers\PenggunaController::class, 'updatePassword'])->name('pengguna.updatePassword');
    Route::post('/pengguna/update-hak-akses', [\App\Http\Controllers\PenggunaController::class, 'updateHakAkses'])->name('pengguna.updateHakAkses');

    // Pengaturan
    Route::get('/pengaturan', [\App\Http\Controllers\PengaturanController::class, 'index'])->name('pengaturan.index');
    Route::post('/pengaturan/update-preferensi', [\App\Http\Controllers\PengaturanController::class, 'updatePreferensi'])->name('pengaturan.updatePreferensi');
    Route::post('/pengaturan/toggle-2fa', [\App\Http\Controllers\PengaturanController::class, 'toggle2fa'])->name('pengaturan.toggle2fa');
    Route::post('/pengaturan/2fa/regenerate-recovery-codes', [\App\Http\Controllers\PengaturanController::class, 'regenerateRecoveryCodes'])->name('pengaturan.regenerateRecoveryCodes');

    // Backup & Pemulihan
    Route::post('/pengaturan/backup', [\App\Http\Controllers\PengaturanController::class, 'createBackup'])->name('pengaturan.backup.create');
    Route::get('/pengaturan/backup/download/{filename}', [\App\Http\Controllers\PengaturanController::class, 'downloadBackup'])->name('pengaturan.backup.download');
    Route::post('/pengaturan/backup/restore', [\App\Http\Controllers\PengaturanController::class, 'restoreBackup'])->name('pengaturan.backup.restore');
    Route::delete('/pengaturan/backup/{filename}', [\App\Http\Controllers\PengaturanController::class, 'deleteBackup'])->name('pengaturan.backup.delete');

    // API & Real-time Features
    Route::get('/api/search-buku', [BukuController::class, 'quickSearch'])->name('buku.quick-search');
    Route::get('/api/notifications', [\App\Http\Controllers\NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/api/notifications/mark-read', [\App\Http\Controllers\NotificationController::class, 'markReadAll'])->name('notifications.markRead');
});
