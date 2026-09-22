<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BukuMasuk extends Model
{
    protected $table = 'buku_masuk';
    public $timestamps = false;

    protected $fillable = ['no_transaksi', 'id_user', 'tanggal_masuk', 'keterangan', 'status'];

    public function details()
    {
        return $this->hasMany(DetailBukuMasuk::class, 'id_buku_masuk');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    /**
     * Generate Nomor Transaksi / Bukti Penerimaan Otomatis (#IN-0001, dst.)
     */
    public static function generateNoTransaksi(): string
    {
        $maxId = (int)(self::max('id') ?? 0);
        $nextNum = max($maxId + 1, self::count() + 1);

        do {
            $candidate = sprintf('#IN-%04d', $nextNum);
            $nextNum++;
        } while (self::where('no_transaksi', $candidate)->exists());

        return $candidate;
    }
}
