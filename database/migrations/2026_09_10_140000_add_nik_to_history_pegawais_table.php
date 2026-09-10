<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('history_pegawais', function (Blueprint $table) {
            if (!Schema::hasColumn('history_pegawais', 'nik')) {
                $table->string('nik', 16)->nullable()->after('nip');
            }
        });
    }

    public function down(): void
    {
        Schema::table('history_pegawais', function (Blueprint $table) {
            $table->dropColumn('nik');
        });
    }
};
