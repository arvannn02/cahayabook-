<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KategoriBuku extends Model
{
    protected $table = 'kategori_buku';
    public $timestamps = false;

    protected $fillable = ['nama_kategori', 'kode_kategori', 'lokasi_rak', 'deskripsi'];

    public function buku()
    {
        return $this->hasMany(Buku::class, 'id_kategori');
    }

    public function getFormattedKodeAttribute()
    {
        if (!empty($this->kode_kategori)) {
            return $this->kode_kategori;
        }
        $initials = strtoupper(substr(preg_replace('/[^a-zA-Z]/', '', $this->nama_kategori), 0, 3));
        return 'KAT - ' . ($initials ?: 'GEN');
    }
}
