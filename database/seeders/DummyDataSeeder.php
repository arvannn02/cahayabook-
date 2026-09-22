<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\KategoriBuku;
use App\Models\Penulis;
use App\Models\Penerbit;
use App\Models\Buku;
use App\Models\StokBuku;

use App\Models\RakBuku;
use App\Models\BukuMasuk;
use App\Models\DetailBukuMasuk;
use App\Models\BukuKeluar;
use App\Models\DetailBukuKeluar;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class DummyDataSeeder extends Seeder
{
    public function run(): void
    {
        if (!Schema::hasColumn('rak_buku', 'kapasitas_max')) {
            Schema::table('rak_buku', function (Blueprint $table) {
                $table->integer('kapasitas_max')->default(100)->after('lokasi');
                $table->string('kategori_buku')->nullable()->after('kapasitas_max');
            });
        }

        Schema::disableForeignKeyConstraints();

        DetailBukuMasuk::query()->delete();
        BukuMasuk::query()->delete();
        Buku::query()->delete();
        KategoriBuku::query()->delete();
        Penulis::query()->delete();
        Penerbit::query()->delete();
        StokBuku::query()->delete();
        RakBuku::query()->delete();

        $rak1 = RakBuku::create([
            'kode_rak' => 'RAK-A01',
            'lokasi' => 'Lantai 1 - Sayap Kiri',
            'kategori_buku' => 'Fiksi & Sastra',
            'kapasitas_max' => 100,
        ]);

        $rak2 = RakBuku::create([
            'kode_rak' => 'RAK-B02',
            'lokasi' => 'Lantai 1 - Tengah',
            'kategori_buku' => 'Sejarah & Biografi',
            'kapasitas_max' => 120,
        ]);

        $rak3 = RakBuku::create([
            'kode_rak' => 'RAK-C05',
            'lokasi' => 'Lantai 2 - Gudang A',
            'kategori_buku' => 'Sains & Teknologi',
            'kapasitas_max' => 150,
        ]);

        $rak4 = RakBuku::create([
            'kode_rak' => 'RAK-D12',
            'lokasi' => 'Lantai 2 - Area Baca',
            'kategori_buku' => 'Buku Anak',
            'kapasitas_max' => 80,
        ]);

        $katFiksi = KategoriBuku::create([
            'nama_kategori' => 'Fiksi & Sastra',
            'kode_kategori' => 'KAT - FKS',
            'lokasi_rak'    => 'A-01 s/d A-05',
        ]);
        $katSains = KategoriBuku::create([
            'nama_kategori' => 'Sains & Teknologi',
            'kode_kategori' => 'KAT - SNT',
            'lokasi_rak'    => 'B-01 s/d B-03',
        ]);
        $katSejarah = KategoriBuku::create([
            'nama_kategori' => 'Sejarah & Biografi',
            'kode_kategori' => 'KAT - SJB',
            'lokasi_rak'    => 'C-01, C-02',
        ]);
        $katKomik = KategoriBuku::create([
            'nama_kategori' => 'Komik & Manga',
            'kode_kategori' => 'KAT - KMK',
            'lokasi_rak'    => null,
        ]);
        $katAnak = KategoriBuku::create([
            'nama_kategori' => 'Buku Anak',
            'kode_kategori' => 'KAT - ANK',
            'lokasi_rak'    => 'D-01 s/d D-04',
        ]);
        $katPsikologi = KategoriBuku::create([
            'nama_kategori' => 'Psikologi',
            'kode_kategori' => 'KAT - PSK',
            'lokasi_rak'    => 'E-01 s/d E-02',
        ]);
        $katKuliner = KategoriBuku::create([
            'nama_kategori' => 'Kuliner',
            'kode_kategori' => 'KAT - KLN',
            'lokasi_rak'    => 'F-01 s/d F-03',
        ]);

        $pen1 = Penulis::create([
            'nama_penulis'    => 'Elena G. Wright',
            'bio'             => 'Fiksi & Sastra Kontemporer',
            'kewarganegaraan' => 'Amerika Serikat',
        ]);
        $pen2 = Penulis::create([
            'nama_penulis'    => 'Raka Wijaya',
            'bio'             => 'Sains Populer & Teknologi',
            'kewarganegaraan' => 'Indonesia',
        ]);
        $pen3 = Penulis::create([
            'nama_penulis'    => 'Siti Markhamah',
            'bio'             => 'Sejarah Nusantara & Budaya',
            'kewarganegaraan' => 'Indonesia',
        ]);
        $pen4 = Penulis::create([
            'nama_penulis'    => 'John McCarthy',
            'bio'             => 'Kecerdasan Buatan & Komputasi',
            'kewarganegaraan' => 'Inggris',
        ]);
        $pen5 = Penulis::create([
            'nama_penulis'    => 'Chef Arnold P.',
            'bio'             => 'Kuliner Nusantara & Fusion',
            'kewarganegaraan' => 'Indonesia',
        ]);

        $pub1 = Penerbit::create([
            'id' => 1,
            'nama_penerbit' => 'Gramedia Pustaka Utama',
            'telepon' => '021-5365011',
            'alamat' => 'Jl. Palmerah Barat No.29–37, Jakarta Pusat'
        ]);
        $pub2 = Penerbit::create([
            'id' => 12,
            'nama_penerbit' => 'Mizan Pustaka',
            'telepon' => '022-7834555',
            'alamat' => 'Jl. Cinambo No.135, Bandung'
        ]);
        $pub3 = Penerbit::create([
            'id' => 45,
            'nama_penerbit' => 'Penerbit Erlangga',
            'telepon' => '021-8717006',
            'alamat' => 'Jl. H. Baping Raya No.100, Jakarta Timur'
        ]);
        $pub4 = Penerbit::create([
            'id' => 50,
            'nama_penerbit' => 'Gramedia',
            'telepon' => '021-5365012',
            'alamat' => 'Jl. Matraman No.46, Jakarta Timur'
        ]);

        $b1 = Buku::create(['id' => 1, 'isbn' => '978-602-123-456-0', 'judul' => 'The Silent Forest', 'id_kategori' => $katFiksi->id, 'id_penulis' => $pen1->id, 'id_penerbit' => $pub1->id, 'id_rak' => $rak1->id, 'harga_beli' => 65000, 'harga_jual' => 98000, 'tahun_terbit' => 2023]);
        StokBuku::create(['id_buku' => $b1->id, 'stok_masuk' => 50, 'stok_keluar' => 8, 'stok_sisa' => 42, 'min_stok' => 10]);

        $b2 = Buku::create(['id' => 2, 'isbn' => '978-602-098-765-4', 'judul' => 'Membangun Fokus', 'id_kategori' => $katPsikologi->id, 'id_penulis' => $pen2->id, 'id_penerbit' => $pub2->id, 'id_rak' => $rak2->id, 'harga_beli' => 85000, 'harga_jual' => 125000, 'tahun_terbit' => 2022]);
        StokBuku::create(['id_buku' => $b2->id, 'stok_masuk' => 15, 'stok_keluar' => 12, 'stok_sisa' => 3, 'min_stok' => 5]);

        $b3 = Buku::create(['id' => 3, 'isbn' => '978-979-555-111-2', 'judul' => 'Jejak Nusantara', 'id_kategori' => $katSejarah->id, 'id_penulis' => $pen3->id, 'id_penerbit' => $pub3->id, 'id_rak' => $rak3->id, 'harga_beli' => 105000, 'harga_jual' => 150000, 'tahun_terbit' => 2021]);
        StokBuku::create(['id_buku' => $b3->id, 'stok_masuk' => 200, 'stok_keluar' => 44, 'stok_sisa' => 156, 'min_stok' => 10]);

        $b4 = Buku::create(['id' => 4, 'isbn' => '978-623-222-333-4', 'judul' => 'Kuliner Tradisional', 'id_kategori' => $katKuliner->id, 'id_penulis' => $pen5->id, 'id_penerbit' => $pub4->id, 'id_rak' => $rak4->id, 'harga_beli' => 145000, 'harga_jual' => 210000, 'tahun_terbit' => 2023]);
        StokBuku::create(['id_buku' => $b4->id, 'stok_masuk' => 100, 'stok_keluar' => 12, 'stok_sisa' => 88, 'min_stok' => 10]);

        if (!Schema::hasColumn('buku_masuk', 'status')) {
            Schema::table('buku_masuk', function (Blueprint $table) {
                $table->string('status')->default('Selesai')->after('keterangan');
            });
        }

        DetailBukuMasuk::query()->delete();
        BukuMasuk::query()->delete();

        $user = \App\Models\User::first();
        $userId = $user ? $user->id : 1;

        $bm1 = BukuMasuk::create(['no_transaksi' => '#IN-0824', 'id_user' => $userId, 'tanggal_masuk' => '2023-10-24', 'keterangan' => 'R-A1', 'status' => 'Selesai']);
        DetailBukuMasuk::create(['id_buku_masuk' => $bm1->id, 'id_buku' => $b1->id, 'jumlah' => 150, 'harga_satuan' => 50000, 'subtotal' => 7500000]);

        $bm2 = BukuMasuk::create(['no_transaksi' => '#IN-0823', 'id_user' => $userId, 'tanggal_masuk' => '2023-10-24', 'keterangan' => 'R-B3', 'status' => 'Selesai']);
        DetailBukuMasuk::create(['id_buku_masuk' => $bm2->id, 'id_buku' => $b2->id, 'jumlah' => 200, 'harga_satuan' => 60000, 'subtotal' => 12000000]);

        $bm3 = BukuMasuk::create(['no_transaksi' => '#IN-0822', 'id_user' => $userId, 'tanggal_masuk' => '2023-10-23', 'keterangan' => 'R-A2', 'status' => 'Diproses']);
        DetailBukuMasuk::create(['id_buku_masuk' => $bm3->id, 'id_buku' => $b3->id, 'jumlah' => 100, 'harga_satuan' => 75000, 'subtotal' => 7500000]);

        $bm4 = BukuMasuk::create(['no_transaksi' => '#IN-0821', 'id_user' => $userId, 'tanggal_masuk' => '2023-10-22', 'keterangan' => 'R-C1', 'status' => 'Selesai']);
        DetailBukuMasuk::create(['id_buku_masuk' => $bm4->id, 'id_buku' => $b1->id, 'jumlah' => 75, 'harga_satuan' => 90000, 'subtotal' => 6750000]);

        if (!Schema::hasColumn('buku_keluar', 'status')) {
            Schema::table('buku_keluar', function (Blueprint $table) {
                $table->string('tujuan')->nullable()->after('keterangan');
                $table->string('expedisi')->nullable()->after('tujuan');
                $table->string('status')->default('Selesai')->after('expedisi');
            });
        }

        DetailBukuKeluar::query()->delete();
        BukuKeluar::query()->delete();

        $bk1 = BukuKeluar::create(['no_transaksi' => '#OUT-0824', 'id_user' => $userId, 'tanggal_keluar' => '2026-09-17', 'tujuan' => 'Toko Cabang A', 'expedisi' => 'JNE Reguler', 'status' => 'Dikirim']);
        DetailBukuKeluar::create(['id_buku_keluar' => $bk1->id, 'id_buku' => $b1->id, 'jumlah' => 50, 'harga_satuan' => 98000, 'subtotal' => 4900000]);

        $bk2 = BukuKeluar::create(['no_transaksi' => '#OUT-0823', 'id_user' => $userId, 'tanggal_keluar' => '2026-09-17', 'tujuan' => 'Distributor Utama', 'expedisi' => 'Kurir Internal', 'status' => 'Selesai']);
        DetailBukuKeluar::create(['id_buku_keluar' => $bk2->id, 'id_buku' => $b2->id, 'jumlah' => 120, 'harga_satuan' => 125000, 'subtotal' => 15000000]);

        $bk3 = BukuKeluar::create(['no_transaksi' => '#OUT-0822', 'id_user' => $userId, 'tanggal_keluar' => '2026-09-16', 'tujuan' => 'Toko Buku Mandiri', 'expedisi' => 'SiCepat', 'status' => 'Diproses']);
        DetailBukuKeluar::create(['id_buku_keluar' => $bk3->id, 'id_buku' => $b3->id, 'jumlah' => 35, 'harga_satuan' => 150000, 'subtotal' => 5250000]);

        $bk4 = BukuKeluar::create(['no_transaksi' => '#OUT-0821', 'id_user' => $userId, 'tanggal_keluar' => '2026-09-15', 'tujuan' => 'Universitas Teknologi', 'expedisi' => 'Kargo Khusus', 'status' => 'Menunggu']);
        DetailBukuKeluar::create(['id_buku_keluar' => $bk4->id, 'id_buku' => $b1->id, 'jumlah' => 200, 'harga_satuan' => 98000, 'subtotal' => 19600000]);

        $bk5 = BukuKeluar::create(['no_transaksi' => '#OUT-0820', 'id_user' => $userId, 'tanggal_keluar' => '2026-09-14', 'tujuan' => 'Toko Cabang B', 'expedisi' => 'JNE Trucking', 'status' => 'Selesai']);
        DetailBukuKeluar::create(['id_buku_keluar' => $bk5->id, 'id_buku' => $b2->id, 'jumlah' => 80, 'harga_satuan' => 125000, 'subtotal' => 10000000]);

        if (!Schema::hasColumn('aktivitas', 'terminal')) {
            Schema::table('aktivitas', function (Blueprint $table) {
                $table->string('terminal')->nullable()->after('detail');
                $table->string('ip_address')->nullable()->after('terminal');
            });
        }

        \App\Models\Aktivitas::query()->delete();

        \App\Models\Aktivitas::create([
            'id_user' => $userId,
            'aksi' => 'Buku Masuk',
            'detail' => 'Menerima 50 copy "Laskar Pelangi" ke Rak A-12',
            'terminal' => 'TERM-03',
            'ip_address' => '192.168.1.105',
            'created_at' => date('Y-m-d 10:42:00')
        ]);

        \App\Models\Aktivitas::create([
            'id_user' => $userId,
            'aksi' => 'Update Stok',
            'detail' => 'Penyesuaian stok opname: Kategori Fiksi (-2 item rusak)',
            'terminal' => 'TERM-01',
            'ip_address' => '192.168.1.102',
            'created_at' => date('Y-m-d 09:15:00')
        ]);

        \App\Models\Aktivitas::create([
            'id_user' => $userId,
            'aksi' => 'Sesi Login',
            'detail' => 'Autentikasi berhasil menggunakan sandi utama.',
            'terminal' => 'TERM-02',
            'ip_address' => '192.168.1.103',
            'created_at' => date('Y-m-d 08:02:00')
        ]);

        Schema::enableForeignKeyConstraints();
    }
}
