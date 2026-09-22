<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BukuKeluar extends Model
{
    protected $table = 'buku_keluar';
    public $timestamps = false;

    protected $fillable = ['no_transaksi', 'id_user', 'tanggal_keluar', 'keterangan', 'tujuan', 'expedisi', 'status'];

    public function details()
    {
        return $this->hasMany(DetailBukuKeluar::class, 'id_buku_keluar');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    /**
     * Generate Nomor Registrasi / Bukti Pengeluaran Otomatis (#OUT-0001, dst.)
     */
    public static function generateNoTransaksi(): string
    {
        $maxId = (int)(self::max('id') ?? 0);
        $nextNum = max($maxId + 1, self::count() + 1);

        do {
            $candidate = sprintf('#OUT-%04d', $nextNum);
            $nextNum++;
        } while (self::where('no_transaksi', $candidate)->exists());

        return $candidate;
    }
}
