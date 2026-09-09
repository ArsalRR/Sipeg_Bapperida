<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\JenisDokumen;

class JenisDokumenSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['nama_jenis' => 'SK CPNS / PNS', 'deskripsi' => 'Surat Keputusan Pengangkatan CPNS & PNS', 'icon' => 'badge', 'warna' => 'blue'],
            ['nama_jenis' => 'SK Kenaikan Pangkat', 'deskripsi' => 'Surat Keputusan Kenaikan Pangkat/Golongan', 'icon' => 'trending-up', 'warna' => 'indigo'],
            ['nama_jenis' => 'SK Jabatan', 'deskripsi' => 'Surat Keputusan Jabatan Struktural / Fungsional', 'icon' => 'briefcase', 'warna' => 'purple'],
            ['nama_jenis' => 'Ijazah Terakhir', 'deskripsi' => 'Dokumen Ijazah Pendidikan Formal Terakhir', 'icon' => 'academic', 'warna' => 'emerald'],
            ['nama_jenis' => 'Transkrip Nilai', 'deskripsi' => 'Transkrip Nilai Akademik Terakhir', 'icon' => 'document-text', 'warna' => 'teal'],
            ['nama_jenis' => 'Kartu Keluarga (KK)', 'deskripsi' => 'Dokumen Asli / Salinan Kartu Keluarga', 'icon' => 'users', 'warna' => 'amber'],
            ['nama_jenis' => 'KTP / Paspor', 'deskripsi' => 'Kartu Tanda Penduduk / Paspor', 'icon' => 'id-card', 'warna' => 'rose'],
            ['nama_jenis' => 'NPWP', 'deskripsi' => 'Nomor Pokok Wajib Pajak', 'icon' => 'credit-card', 'warna' => 'cyan'],
            ['nama_jenis' => 'Sertifikat Pelatihan / Diklat', 'deskripsi' => 'Sertifikat Keikutsertaan Diklat / Workshop / Bimtek', 'icon' => 'certificate', 'warna' => 'sky'],
            ['nama_jenis' => 'Dokumen Lainnya', 'deskripsi' => 'Berkas atau Dokumen pendukung lainnya', 'icon' => 'folder', 'warna' => 'slate'],
        ];

        foreach ($data as $item) {
            JenisDokumen::firstOrCreate(['nama_jenis' => $item['nama_jenis']], $item);
        }
    }
}
