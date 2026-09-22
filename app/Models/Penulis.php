<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Penulis extends Model
{
    protected $table = 'penulis';
    public $timestamps = false;

    protected $fillable = ['nama_penulis', 'bio', 'kewarganegaraan', 'foto'];
    protected $appends = ['initials', 'flag_url'];

    public function buku()
    {
        return $this->hasMany(Buku::class, 'id_penulis');
    }

    public function getInitialsAttribute()
    {
        $words = explode(' ', trim($this->nama_penulis));
        if (count($words) >= 2) {
            return strtoupper(substr($words[0], 0, 1) . substr(end($words), 0, 1));
        }
        return strtoupper(substr($this->nama_penulis, 0, 2));
    }

    public function getFlagUrlAttribute(): ?string
    {
        if (empty($this->kewarganegaraan)) {
            return null;
        }
        $country = \App\Http\Controllers\PenulisController::findCountry($this->kewarganegaraan);
        return $country['flag_url'] ?? null;
    }
}
