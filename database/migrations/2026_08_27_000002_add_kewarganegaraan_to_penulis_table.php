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
        Schema::table('penulis', function (Blueprint $table) {
            if (!Schema::hasColumn('penulis', 'kewarganegaraan')) {
                $table->string('kewarganegaraan', 100)->nullable()->after('bio');
            }
            if (!Schema::hasColumn('penulis', 'foto')) {
                $table->string('foto')->nullable()->after('kewarganegaraan');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('penulis', function (Blueprint $table) {
            if (Schema::hasColumn('penulis', 'kewarganegaraan')) {
                $table->dropColumn('kewarganegaraan');
            }
            if (Schema::hasColumn('penulis', 'foto')) {
                $table->dropColumn('foto');
            }
        });
    }
};
