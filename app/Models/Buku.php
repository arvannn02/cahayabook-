<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Buku extends Model
{
    protected $table = 'buku';
    public $timestamps = false;

    protected $fillable = [
        'isbn', 'judul', 'id_kategori', 'id_penulis',
        'id_penerbit', 'id_rak', 'harga_beli', 'harga_jual',
        'tahun_terbit', 'sampul',
    ];

    public function kategori()
    {
        return $this->belongsTo(KategoriBuku::class, 'id_kategori');
    }

    public function penulis()
    {
        return $this->belongsTo(Penulis::class, 'id_penulis');
    }

    public function penerbit()
    {
        return $this->belongsTo(Penerbit::class, 'id_penerbit');
    }

    public function rak()
    {
        return $this->belongsTo(RakBuku::class, 'id_rak');
    }

    public function stok()
    {
        return $this->hasOne(StokBuku::class, 'id_buku');
    }
}
