<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailBukuMasuk extends Model
{
    protected $table = 'detail_buku_masuk';
    public $timestamps = false;

    protected $fillable = ['id_buku_masuk', 'id_buku', 'jumlah', 'harga_satuan', 'subtotal'];

    public function bukuMasuk()
    {
        return $this->belongsTo(BukuMasuk::class, 'id_buku_masuk');
    }

    public function buku()
    {
        return $this->belongsTo(Buku::class, 'id_buku');
    }
}
