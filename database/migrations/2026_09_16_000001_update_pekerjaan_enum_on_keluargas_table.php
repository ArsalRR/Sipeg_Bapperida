<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $allowed = [
            'ASN',
            'Swasta',
            'BUMN',
            'BUMD',
            'IRT',
            'Pelajar / Mahasiswa',
            'Tidak Bekerja',
            'Ayah',
            'Ibu',
            'Pensiunan',
            'Wiraswasta',
            'Lainnya'
        ];

        // 1. Clean existing non-matching data in keluargas table so alter table succeeds
        $rows = DB::table('keluargas')->get();
        foreach ($rows as $row) {
            $p = trim((string)$row->pekerjaan);
            if (empty($p)) {
                continue;
            }
            if (!in_array($p, $allowed, true)) {
                $newVal = 'Lainnya';
                if (preg_match('/pns|pppk|asn|tni|polri|pegawai negeri/i', $p)) {
                    $newVal = 'ASN';
                } elseif (preg_match('/swasta|karyawan/i', $p)) {
                    $newVal = 'Swasta';
                } elseif (preg_match('/bumn/i', $p)) {
                    $newVal = 'BUMN';
                } elseif (preg_match('/bumd/i', $p)) {
                    $newVal = 'BUMD';
                } elseif (preg_match('/irt|ibu rumah|rumah tangga/i', $p)) {
                    $newVal = 'IRT';
                } elseif (preg_match('/pelajar|mahasiswa|sekolah|sd|smp|sma/i', $p)) {
                    $newVal = 'Pelajar / Mahasiswa';
                } elseif (preg_match('/tidak|belum/i', $p)) {
                    $newVal = 'Tidak Bekerja';
                } elseif (preg_match('/ayah/i', $p)) {
                    $newVal = 'Ayah';
                } elseif (preg_match('/ibu/i', $p)) {
                    $newVal = 'Ibu';
                } elseif (preg_match('/pensiun/i', $p)) {
                    $newVal = 'Pensiunan';
                } elseif (preg_match('/wira|usaha|dagang/i', $p)) {
                    $newVal = 'Wiraswasta';
                }

                DB::table('keluargas')->where('id', $row->id)->update(['pekerjaan' => $newVal]);
            }
        }

        // 2. Safely alter column to ENUM
        DB::statement("ALTER TABLE keluargas MODIFY COLUMN pekerjaan ENUM('ASN', 'Swasta', 'BUMN', 'BUMD', 'IRT', 'Pelajar / Mahasiswa', 'Tidak Bekerja', 'Ayah', 'Ibu', 'Pensiunan', 'Wiraswasta', 'Lainnya') NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE keluargas MODIFY COLUMN pekerjaan VARCHAR(255) NULL");
    }
};
