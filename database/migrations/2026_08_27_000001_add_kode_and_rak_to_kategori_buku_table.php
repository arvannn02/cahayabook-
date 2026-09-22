<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('kategori_buku', function (Blueprint $table) {
            if (!Schema::hasColumn('kategori_buku', 'kode_kategori')) {
                $table->string('kode_kategori', 50)->nullable()->after('nama_kategori');
            }
            if (!Schema::hasColumn('kategori_buku', 'lokasi_rak')) {
                $table->string('lokasi_rak', 100)->nullable()->after('kode_kategori');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kategori_buku', function (Blueprint $table) {
            if (Schema::hasColumn('kategori_buku', 'kode_kategori')) {
                $table->dropColumn('kode_kategori');
            }
            if (Schema::hasColumn('kategori_buku', 'lokasi_rak')) {
                $table->dropColumn('lokasi_rak');
            }
        });
    }
};
