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
            if (!Schema::hasColumn('pegawais', 'mkg_tahun')) {
                $table->unsignedSmallInteger('mkg_tahun')->nullable()->after('golongan');
            }
            if (!Schema::hasColumn('pegawais', 'mkg_bulan')) {
                $table->unsignedTinyInteger('mkg_bulan')->nullable()->after('mkg_tahun');
            }
        });

        Schema::table('history_pegawais', function (Blueprint $table) {
            if (!Schema::hasColumn('history_pegawais', 'mkg_tahun')) {
                $table->unsignedSmallInteger('mkg_tahun')->nullable()->after('golongan');
            }
            if (!Schema::hasColumn('history_pegawais', 'mkg_bulan')) {
                $table->unsignedTinyInteger('mkg_bulan')->nullable()->after('mkg_tahun');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pegawais', function (Blueprint $table) {
            if (Schema::hasColumn('pegawais', 'mkg_bulan')) {
                $table->dropColumn('mkg_bulan');
            }
            if (Schema::hasColumn('pegawais', 'mkg_tahun')) {
                $table->dropColumn('mkg_tahun');
            }
        });

        Schema::table('history_pegawais', function (Blueprint $table) {
            if (Schema::hasColumn('history_pegawais', 'mkg_bulan')) {
                $table->dropColumn('mkg_bulan');
            }
            if (Schema::hasColumn('history_pegawais', 'mkg_tahun')) {
                $table->dropColumn('mkg_tahun');
            }
        });
    }
};
