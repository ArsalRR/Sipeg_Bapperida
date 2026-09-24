<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Tambah kolom bidang_id ke tabel jabatans (nullable, FK ke bidangs)
        if (Schema::hasTable('jabatans') && !Schema::hasColumn('jabatans', 'bidang_id')) {
            Schema::table('jabatans', function (Blueprint $table) {
                $table->foreignId('bidang_id')->nullable()->after('unit_kerja')->constrained('bidangs')->nullOnDelete();
            });
        }

        // 2. Isi otomatis bidang_id berdasarkan singkatan bidang yang cocok dengan unit_kerja jabatan
        if (Schema::hasTable('bidangs') && Schema::hasTable('jabatans')) {
            $bidangs = DB::table('bidangs')->get();
            foreach ($bidangs as $bidang) {
                // Cocokkan jabatan yang unit_kerjanya sama persis dengan singkatan bidang
                DB::table('jabatans')
                    ->where('unit_kerja', $bidang->singkatan)
                    ->whereNull('bidang_id')
                    ->update(['bidang_id' => $bidang->id]);
            }
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('jabatans') && Schema::hasColumn('jabatans', 'bidang_id')) {
            Schema::table('jabatans', function (Blueprint $table) {
                $table->dropForeign(['bidang_id']);
                $table->dropColumn('bidang_id');
            });
        }
    }
};
