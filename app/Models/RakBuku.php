<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RakBuku extends Model
{
    protected $table = 'rak_buku';
    public $timestamps = false;

    protected $fillable = ['kode_rak', 'lokasi', 'kapasitas_max', 'kategori_buku'];

    public function buku()
    {
        return $this->hasMany(Buku::class, 'id_rak');
    }

    /**
     * Generate Kode Rak otomatis secara berurutan sesuai pola awal (RAK-A01, RAK-B02, dst.)
     *
     * @param string|null $kategoriName
     * @return string
     */
    public static function generateKodeRak(?string $kategoriName = null): string
    {
        $sectionLetter = null;

        if (!empty($kategoriName)) {
            // 1. Cek apakah kategori ini sudah memiliki rak dengan kode standar RAK-[A-Z][0-9]+
            $existingWithCat = self::where('kategori_buku', $kategoriName)
                ->where('kode_rak', 'like', 'RAK-%')
                ->get();

            foreach ($existingWithCat as $r) {
                if (preg_match('/^RAK-([A-Z])([0-9]+)$/i', trim($r->kode_rak), $m)) {
                    $sectionLetter = strtoupper($m[1]);
                    break;
                }
            }

            // 2. Jika belum ada rak untuk kategori ini, cek dari tabel kategori_buku
            if (!$sectionLetter) {
                $kat = \App\Models\KategoriBuku::where('nama_kategori', $kategoriName)->first();
                if ($kat) {
                    if (!empty($kat->lokasi_rak) && preg_match('/^([A-Z])[-0-9]/i', trim($kat->lokasi_rak), $locM)) {
                        $sectionLetter = strtoupper($locM[1]);
                    }
                }
            }
        }

        // 3. Jika belum mendapatkan huruf seksi, cari huruf yang belum dipakai dari A sampai Z
        if (!$sectionLetter) {
            $usedLetters = [];
            $allRaks = self::where('kode_rak', 'like', 'RAK-%')->pluck('kode_rak');
            foreach ($allRaks as $k) {
                if (preg_match('/^RAK-([A-Z])([0-9]+)$/i', trim($k), $m)) {
                    $usedLetters[strtoupper($m[1])] = true;
                }
            }

            // Kategori yang sudah memiliki lokasi_rak berpola huruf
            $allKat = \App\Models\KategoriBuku::all();
            foreach ($allKat as $k) {
                if (!empty($k->lokasi_rak) && preg_match('/^([A-Z])[-0-9]/i', trim($k->lokasi_rak), $m)) {
                    $usedLetters[strtoupper($m[1])] = true;
                }
            }

            foreach (range('A', 'Z') as $char) {
                if (!isset($usedLetters[$char])) {
                    $sectionLetter = $char;
                    break;
                }
            }

            if (!$sectionLetter) {
                $sectionLetter = 'Z';
            }
        }

        // 4. Cari nomor urut tertinggi untuk huruf seksi ini
        $existingCodes = self::where('kode_rak', 'like', 'RAK-' . $sectionLetter . '%')->pluck('kode_rak');
        $maxNum = 0;
        foreach ($existingCodes as $c) {
            if (preg_match('/^RAK-' . $sectionLetter . '([0-9]+)$/i', trim($c), $m)) {
                $num = (int)$m[1];
                if ($num > $maxNum) {
                    $maxNum = $num;
                }
            }
        }

        $nextNum = $maxNum + 1;

        // Pastikan kode benar-benar unik di database
        do {
            $candidate = sprintf('RAK-%s%02d', $sectionLetter, $nextNum);
            $nextNum++;
        } while (self::where('kode_rak', $candidate)->exists());

        return $candidate;
    }
}
