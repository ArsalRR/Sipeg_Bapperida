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
        Schema::create('jenis_dokumens', function (Blueprint $table) {
            $table->id();
            $table->string('nama_jenis')->unique();
            $table->string('deskripsi')->nullable();
            $table->string('icon')->default('folder'); // folder, document, certificate, badge, id, etc.
            $table->string('warna')->default('blue'); // blue, indigo, emerald, purple, amber, rose, cyan
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jenis_dokumens');
    }
};
