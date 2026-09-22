<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailBukuKeluar extends Model
{
    protected $table = 'detail_buku_keluar';
    public $timestamps = false;

    protected $fillable = ['id_buku_keluar', 'id_buku', 'jumlah', 'harga_satuan', 'subtotal'];

    public function bukuKeluar()
    {
        return $this->belongsTo(BukuKeluar::class, 'id_buku_keluar');
    }

    public function buku()
    {
        return $this->belongsTo(Buku::class, 'id_buku');
    }
}
