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
            $table->string('nip', 18)->nullable()->change();
            $table->string('nik', 16)->nullable()->change();
            $table->text('alamat')->nullable()->change();
            $table->string('tempat_lahir')->nullable()->change();
            $table->date('tanggal_lahir')->nullable()->change();
            $table->enum('jenis_kelamin', ['Laki-laki', 'Perempuan'])->nullable()->change();
            $table->string('agama')->nullable()->change();
            $table->enum('status_kepegawaian', ['PNS', 'PPPK', 'CPNS', 'PPPK Paruh Waktu', 'Non ASN'])->nullable()->change();
            $table->foreignId('jabatan_id')->nullable()->change();
            $table->string('golongan')->nullable()->change();
            $table->enum('status_pernikahan', ['Lajang', 'Menikah', 'Cerai Hidup', 'Cerai Mati'])->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pegawais', function (Blueprint $table) {
            //
        });
    }
};
