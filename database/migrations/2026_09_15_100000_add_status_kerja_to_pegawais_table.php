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
        Schema::table('pegawais', function (Blueprint $table) {
            $table->enum('status_kerja', ['Aktif', 'Tidak Aktif'])->default('Aktif')->after('status_pernikahan');
        });

        Schema::table('history_pegawais', function (Blueprint $table) {
            $table->enum('status_kerja', ['Aktif', 'Tidak Aktif'])->default('Aktif')->after('status_pernikahan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pegawais', function (Blueprint $table) {
            $table->dropColumn('status_kerja');
        });

        Schema::table('history_pegawais', function (Blueprint $table) {
            $table->dropColumn('status_kerja');
        });
    }
};
