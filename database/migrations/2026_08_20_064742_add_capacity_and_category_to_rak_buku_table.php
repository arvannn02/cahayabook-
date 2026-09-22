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
        Schema::table('rak_buku', function (Blueprint $table) {
            if (!Schema::hasColumn('rak_buku', 'kapasitas_max')) {
                $table->integer('kapasitas_max')->default(100)->after('lokasi');
            }
            if (!Schema::hasColumn('rak_buku', 'kategori_buku')) {
                $table->string('kategori_buku')->nullable()->after('kapasitas_max');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rak_buku', function (Blueprint $table) {
            $table->dropColumn(['kapasitas_max', 'kategori_buku']);
        });
    }
};
