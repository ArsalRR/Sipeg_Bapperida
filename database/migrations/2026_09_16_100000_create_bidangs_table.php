<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('bidangs')) {
            Schema::create('bidangs', function (Blueprint $table) {
                $table->id();
                $table->string('nama_bidang');
                $table->string('singkatan')->unique();
                $table->timestamps();
            });
        }

        if (Schema::hasTable('pegawais') && !Schema::hasColumn('pegawais', 'bidang_id')) {
            Schema::table('pegawais', function (Blueprint $table) {
                $table->foreignId('bidang_id')->nullable()->after('jabatan_id')->constrained('bidangs')->nullOnDelete();
            });
        }
    }

    public function down(): void
    {
        Schema::table('pegawais', function (Blueprint $table) {
            $table->dropForeign(['bidang_id']);
            $table->dropColumn('bidang_id');
        });

        Schema::dropIfExists('bidangs');
    }
};
