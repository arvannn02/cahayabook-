<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Penerbit extends Model
{
    protected $table = 'penerbit';
    public $timestamps = false;

    protected $fillable = ['nama_penerbit', 'alamat', 'telepon'];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function buku()
    {
        return $this->hasMany(Buku::class, 'id_penerbit');
    }
}
