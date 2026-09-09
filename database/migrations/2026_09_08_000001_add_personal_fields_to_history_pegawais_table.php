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
            $table->string('nama')->nullable()->after('user_id');
            $table->string('nip')->nullable()->after('nama');
            $table->string('tempat_lahir')->nullable()->after('nip');
            $table->date('tanggal_lahir')->nullable()->after('tempat_lahir');
            $table->enum('jenis_kelamin', ['Laki-laki', 'Perempuan'])->nullable()->after('tanggal_lahir');
            $table->string('agama')->nullable()->after('jenis_kelamin');
            $table->text('alamat')->nullable()->after('agama');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('history_pegawais', function (Blueprint $table) {
            $table->dropColumn(['nama', 'nip', 'tempat_lahir', 'tanggal_lahir', 'jenis_kelamin', 'agama', 'alamat']);
        });
    }
};
