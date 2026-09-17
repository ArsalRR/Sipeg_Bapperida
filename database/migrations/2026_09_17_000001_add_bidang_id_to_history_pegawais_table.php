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
        Schema::table('history_pegawais', function (Blueprint $table) {
            $table->foreignId('bidang_id')->nullable()->after('jabatan_id')->constrained('bidangs')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('history_pegawais', function (Blueprint $table) {
            $table->dropForeign(['bidang_id']);
            $table->dropColumn('bidang_id');
        });
    }
};
