<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('jabatans', function (Blueprint $table) {
            $table->foreignId('parent_id')->nullable()->after('id')->constrained('jabatans')->onDelete('set null');
            $table->unsignedInteger('kelas_jabatan')->nullable()->after('jenis_jabatan');
            $table->unsignedInteger('kebutuhan')->default(1)->after('kelas_jabatan');
            $table->string('kategori_warna')->nullable()->after('kebutuhan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jabatans', function (Blueprint $table) {
            $table->dropForeign(['parent_id']);
            $table->dropColumn(['parent_id', 'kelas_jabatan', 'kebutuhan', 'kategori_warna']);
        });
    }
};
