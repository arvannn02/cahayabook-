<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StokBuku extends Model
{
    protected $table = 'stok_buku';
    public $timestamps = false;
    const UPDATED_AT = 'updated_at';

    protected $fillable = ['id_buku', 'stok_masuk', 'stok_keluar', 'stok_sisa', 'min_stok'];

    public function buku()
    {
        return $this->belongsTo(Buku::class, 'id_buku');
    }
}
