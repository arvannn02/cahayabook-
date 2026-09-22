<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        RateLimiter::for('login', function ($request) {
            return Limit::perMinutes(5, 5)->by(
                strtolower((string) $request->input('email')).'|'.$request->ip()
            );
        });

        // --- AUTOMATIC ACTIVITY LOGGING ---
        \App\Models\Buku::created(function ($model) {
            \App\Models\Aktivitas::log('Tambah Buku', "Menambahkan buku baru \"{$model->judul}\" (ISBN: {$model->isbn})");
        });
        \App\Models\Buku::updated(function ($model) {
            \App\Models\Aktivitas::log('Ubah Buku', "Memperbarui data buku \"{$model->judul}\"");
        });
        \App\Models\Buku::deleted(function ($model) {
            \App\Models\Aktivitas::log('Hapus Buku', "Menghapus data buku \"{$model->judul}\"");
        });

        \App\Models\KategoriBuku::created(function ($model) {
            \App\Models\Aktivitas::log('Tambah Kategori', "Menambahkan kategori buku baru: \"{$model->nama_kategori}\"");
        });
        \App\Models\KategoriBuku::updated(function ($model) {
            \App\Models\Aktivitas::log('Ubah Kategori', "Memperbarui kategori buku \"{$model->nama_kategori}\"");
        });
        \App\Models\KategoriBuku::deleted(function ($model) {
            \App\Models\Aktivitas::log('Hapus Kategori', "Menghapus kategori buku \"{$model->nama_kategori}\"");
        });

        \App\Models\Penulis::created(function ($model) {
            \App\Models\Aktivitas::log('Tambah Penulis', "Menambahkan data penulis baru: \"{$model->nama_penulis}\"");
        });
        \App\Models\Penulis::updated(function ($model) {
            \App\Models\Aktivitas::log('Ubah Penulis', "Memperbarui data penulis \"{$model->nama_penulis}\"");
        });
        \App\Models\Penulis::deleted(function ($model) {
            \App\Models\Aktivitas::log('Hapus Penulis', "Menghapus data penulis \"{$model->nama_penulis}\"");
        });

        \App\Models\Penerbit::created(function ($model) {
            \App\Models\Aktivitas::log('Tambah Penerbit', "Menambahkan penerbit baru: \"{$model->nama_penerbit}\"");
        });
        \App\Models\Penerbit::updated(function ($model) {
            \App\Models\Aktivitas::log('Ubah Penerbit', "Memperbarui data penerbit \"{$model->nama_penerbit}\"");
        });
        \App\Models\Penerbit::deleted(function ($model) {
            \App\Models\Aktivitas::log('Hapus Penerbit', "Menghapus data penerbit \"{$model->nama_penerbit}\"");
        });

        \App\Models\RakBuku::created(function ($model) {
            \App\Models\Aktivitas::log('Tambah Rak', "Menambahkan lokasi rak baru: \"{$model->kode_rak}\" ({$model->nama_rak})");
        });
        \App\Models\RakBuku::updated(function ($model) {
            \App\Models\Aktivitas::log('Ubah Rak', "Memperbarui lokasi rak \"{$model->kode_rak}\" ({$model->nama_rak})");
        });
        \App\Models\RakBuku::deleted(function ($model) {
            \App\Models\Aktivitas::log('Hapus Rak', "Menghapus lokasi rak \"{$model->kode_rak}\"");
        });

        \App\Models\BukuMasuk::created(function ($model) {
            $noTrx = ltrim($model->no_transaksi, '#');
            \App\Models\Aktivitas::log('Buku Masuk', "Mencatat transaksi buku masuk #{$noTrx} (Status: {$model->status})");
        });
        \App\Models\BukuMasuk::updated(function ($model) {
            $noTrx = ltrim($model->no_transaksi, '#');
            \App\Models\Aktivitas::log('Update Buku Masuk', "Transaksi buku masuk #{$noTrx} diperbarui (Status: {$model->status})");
        });
        \App\Models\BukuMasuk::deleted(function ($model) {
            $noTrx = ltrim($model->no_transaksi, '#');
            \App\Models\Aktivitas::log('Hapus Transaksi', "Menghapus transaksi buku masuk #{$noTrx}");
        });

        \App\Models\BukuKeluar::created(function ($model) {
            $noTrx = ltrim($model->no_transaksi, '#');
            $tujuan = $model->tujuan ? "ke {$model->tujuan}" : '';
            \App\Models\Aktivitas::log('Buku Keluar', "Mencatat transaksi buku keluar #{$noTrx} {$tujuan} (Status: {$model->status})");
        });
        \App\Models\BukuKeluar::updated(function ($model) {
            $noTrx = ltrim($model->no_transaksi, '#');
            \App\Models\Aktivitas::log('Update Buku Keluar', "Transaksi buku keluar #{$noTrx} diperbarui (Status: {$model->status})");
        });
        \App\Models\BukuKeluar::deleted(function ($model) {
            $noTrx = ltrim($model->no_transaksi, '#');
            \App\Models\Aktivitas::log('Hapus Transaksi', "Menghapus transaksi buku keluar #{$noTrx}");
        });

        \App\Models\StokBuku::updated(function ($model) {
            // Catat saat dilakukan perubahan stok manual di modul Stok
            if (request() && request()->is('*stok*')) {
                $bukuJudul = $model->buku ? $model->buku->judul : "ID #{$model->id_buku}";
                \App\Models\Aktivitas::log('Update Stok', "Penyesuaian stok opname buku \"{$bukuJudul}\" (Sisa: {$model->stok_sisa}, Min: {$model->min_stok})");
            }
        });
    }
}
