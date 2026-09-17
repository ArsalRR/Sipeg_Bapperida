<?php

namespace Database\Seeders;

use App\Models\Jabatan;
use Illuminate\Database\Seeder;

class JabatanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Update legacy short unit_kerja names in DB to official full names
        $unitReplacements = [
            'Bidang 1' => 'Bidang Pemerintahan & Pembangunan Manusia',
            'Bidang 2' => 'Bidang Perekonomian, SDA, Infrastruktur & Kewilayahan',
            'Bidang 3' => 'Bidang Perencanaan, Pengendalian & Evaluasi',
            'Bidang 4' => 'Bidang Riset & Inovasi Daerah',
            'Subbag 1' => 'Subbag Umum & Kepegawaian',
            'Subbag 2' => 'Subbag Perencanaan Evaluasi & Keuangan',
        ];

        foreach ($unitReplacements as $old => $new) {
            Jabatan::where('unit_kerja', $old)
                ->orWhere('unit_kerja', 'LIKE', $old . ' %')
                ->orWhere('unit_kerja', 'LIKE', $old . ' - %')
                ->update(['unit_kerja' => $new]);
        }

        $defaults = [
            // TOP LEVEL & ESELON
            ['nama_jabatan' => 'Kepala Badan Perencanaan Pembangunan, Riset, dan Inovasi Daerah', 'jenis_jabatan' => 'Struktural', 'kelas_jabatan' => 14, 'kebutuhan' => 1, 'unit' => 'sekretariat'],
            ['nama_jabatan' => 'Sekretaris Badan Perencanaan Pembangunan, Riset, dan Inovasi Daerah', 'jenis_jabatan' => 'Struktural', 'kelas_jabatan' => 12, 'kebutuhan' => 1, 'unit' => 'sekretariat'],
            ['nama_jabatan' => 'JF Perencana Ahli Madya', 'jenis_jabatan' => 'Fungsional', 'kelas_jabatan' => 11, 'kebutuhan' => 2, 'unit' => 'sekretariat'],

            // SUBBAG 1: UMUM DAN KEPEGAWAIAN
            ['nama_jabatan' => 'Kepala Sub Bagian Umum dan Kepegawaian', 'jenis_jabatan' => 'Struktural', 'kelas_jabatan' => 9, 'kebutuhan' => 1, 'unit' => 'umum'],
            ['nama_jabatan' => 'JF Arsiparis Pelaksana', 'jenis_jabatan' => 'Fungsional', 'kelas_jabatan' => 6, 'kebutuhan' => 1, 'unit' => 'umum'],
            ['nama_jabatan' => 'Penelaah Teknis Kebijakan', 'jenis_jabatan' => 'Pelaksana', 'kelas_jabatan' => 7, 'kebutuhan' => 2, 'unit' => 'umum'],
            ['nama_jabatan' => 'Pengolah Data dan Informasi', 'jenis_jabatan' => 'Pelaksana', 'kelas_jabatan' => 6, 'kebutuhan' => 1, 'unit' => 'umum'],
            ['nama_jabatan' => 'Pengadministrasi Perkantoran', 'jenis_jabatan' => 'Pelaksana', 'kelas_jabatan' => 5, 'kebutuhan' => 1, 'unit' => 'umum'],

            // SUBBAG 2: PERENCANAAN EVALUASI DAN KEUANGAN
            ['nama_jabatan' => 'Kepala Sub Bagian Perencanaan Evaluasi dan Keuangan', 'jenis_jabatan' => 'Struktural', 'kelas_jabatan' => 9, 'kebutuhan' => 1, 'unit' => 'perencanaan_evaluasi'],
            ['nama_jabatan' => 'JF Pranata Komputer Pertama', 'jenis_jabatan' => 'Fungsional', 'kelas_jabatan' => 8, 'kebutuhan' => 1, 'unit' => 'perencanaan_evaluasi'],
            ['nama_jabatan' => 'Penelaah Teknis Kebijakan', 'jenis_jabatan' => 'Pelaksana', 'kelas_jabatan' => 7, 'kebutuhan' => 2, 'unit' => 'perencanaan_evaluasi'],
            ['nama_jabatan' => 'Pengolah Data dan Informasi', 'jenis_jabatan' => 'Pelaksana', 'kelas_jabatan' => 6, 'kebutuhan' => 1, 'unit' => 'perencanaan_evaluasi'],
            ['nama_jabatan' => 'JF Pranata Komputer Muda', 'jenis_jabatan' => 'Fungsional', 'kelas_jabatan' => 9, 'kebutuhan' => 1, 'unit' => 'perencanaan_evaluasi'],

            // BIDANG 1: PEMERINTAHAN DAN PEMBANGUNAN MANUSIA
            ['nama_jabatan' => 'Kepala Bidang Pemerintahan dan Pembangunan Manusia', 'jenis_jabatan' => 'Struktural', 'kelas_jabatan' => 11, 'kebutuhan' => 1, 'unit' => 'ppm'],
            ['nama_jabatan' => 'JF Perencana Ahli Muda', 'jenis_jabatan' => 'Fungsional', 'kelas_jabatan' => 10, 'kebutuhan' => 2, 'unit' => 'ppm'],
            ['nama_jabatan' => 'JF Perencana Ahli Pertama', 'jenis_jabatan' => 'Fungsional', 'kelas_jabatan' => 8, 'kebutuhan' => 1, 'unit' => 'ppm'],
            ['nama_jabatan' => 'JF Pranata Komputer Muda', 'jenis_jabatan' => 'Fungsional', 'kelas_jabatan' => 9, 'kebutuhan' => 1, 'unit' => 'ppm'],
            ['nama_jabatan' => 'JF Pranata Komputer Pertama', 'jenis_jabatan' => 'Fungsional', 'kelas_jabatan' => 8, 'kebutuhan' => 1, 'unit' => 'ppm'],
            ['nama_jabatan' => 'Penelaah Teknis Kebijakan', 'jenis_jabatan' => 'Pelaksana', 'kelas_jabatan' => 7, 'kebutuhan' => 2, 'unit' => 'ppm'],
            ['nama_jabatan' => 'Pengolah Data dan Informasi', 'jenis_jabatan' => 'Pelaksana', 'kelas_jabatan' => 6, 'kebutuhan' => 1, 'unit' => 'ppm'],

            // BIDANG 2: PEREKONOMIAN, SDA, INFRASTRUKTUR DAN KEWILAYAHAN
            ['nama_jabatan' => 'Kepala Bidang Perekonomian, Sumber Daya Alam, Infrastruktur dan Kewilayahan', 'jenis_jabatan' => 'Struktural', 'kelas_jabatan' => 11, 'kebutuhan' => 1, 'unit' => 'ekonomi'],
            ['nama_jabatan' => 'JF Perencana Ahli Muda', 'jenis_jabatan' => 'Fungsional', 'kelas_jabatan' => 10, 'kebutuhan' => 2, 'unit' => 'ekonomi'],
            ['nama_jabatan' => 'JF Perencana Ahli Pertama', 'jenis_jabatan' => 'Fungsional', 'kelas_jabatan' => 8, 'kebutuhan' => 2, 'unit' => 'ekonomi'],
            ['nama_jabatan' => 'JF Pranata Komputer Muda', 'jenis_jabatan' => 'Fungsional', 'kelas_jabatan' => 9, 'kebutuhan' => 1, 'unit' => 'ekonomi'],
            ['nama_jabatan' => 'JF Pranata Komputer Pertama', 'jenis_jabatan' => 'Fungsional', 'kelas_jabatan' => 8, 'kebutuhan' => 1, 'unit' => 'ekonomi'],
            ['nama_jabatan' => 'Penelaah Teknis Kebijakan', 'jenis_jabatan' => 'Pelaksana', 'kelas_jabatan' => 7, 'kebutuhan' => 2, 'unit' => 'ekonomi'],
            ['nama_jabatan' => 'Pengolah Data dan Informasi', 'jenis_jabatan' => 'Pelaksana', 'kelas_jabatan' => 6, 'kebutuhan' => 1, 'unit' => 'ekonomi'],

            // BIDANG 3: PERENCANAAN, PENGENDALIAN DAN EVALUASI PEMBANGUNAN DAERAH
            ['nama_jabatan' => 'Kepala Bidang Perencanaan, Pengendalian dan Evaluasi Pembangunan Daerah', 'jenis_jabatan' => 'Struktural', 'kelas_jabatan' => 11, 'kebutuhan' => 1, 'unit' => 'ppepd'],
            ['nama_jabatan' => 'JF Perencana Ahli Muda', 'jenis_jabatan' => 'Fungsional', 'kelas_jabatan' => 10, 'kebutuhan' => 1, 'unit' => 'ppepd'],
            ['nama_jabatan' => 'JF Perencana Ahli Pertama', 'jenis_jabatan' => 'Fungsional', 'kelas_jabatan' => 8, 'kebutuhan' => 1, 'unit' => 'ppepd'],
            ['nama_jabatan' => 'JF Pranata Komputer Ahli Muda', 'jenis_jabatan' => 'Fungsional', 'kelas_jabatan' => 9, 'kebutuhan' => 1, 'unit' => 'ppepd'],
            ['nama_jabatan' => 'JF Pranata Komputer Ahli Pertama', 'jenis_jabatan' => 'Fungsional', 'kelas_jabatan' => 8, 'kebutuhan' => 2, 'unit' => 'ppepd'],
            ['nama_jabatan' => 'Penelaah Teknis Kebijakan', 'jenis_jabatan' => 'Pelaksana', 'kelas_jabatan' => 7, 'kebutuhan' => 2, 'parent' => 'Kepala Bidang Perencanaan', 'unit' => 'ppepd'],
            ['nama_jabatan' => 'Pengolah Data dan Informasi', 'jenis_jabatan' => 'Pelaksana', 'kelas_jabatan' => 6, 'kebutuhan' => 1, 'unit' => 'ppepd'],

            // BIDANG 4: RISET DAN INOVASI DAERAH
            ['nama_jabatan' => 'Kepala Bidang Riset dan Inovasi Daerah', 'jenis_jabatan' => 'Struktural', 'kelas_jabatan' => 11, 'kebutuhan' => 1, 'unit' => 'litbang'],
            ['nama_jabatan' => 'JF Peneliti Ahli Muda', 'jenis_jabatan' => 'Fungsional', 'kelas_jabatan' => 9, 'kebutuhan' => 2, 'unit' => 'litbang'],
            ['nama_jabatan' => 'JF Peneliti Ahli Pertama', 'jenis_jabatan' => 'Fungsional', 'kelas_jabatan' => 8, 'kebutuhan' => 2, 'unit' => 'litbang'],
            ['nama_jabatan' => 'JF Pranata Komputer Ahli Muda', 'jenis_jabatan' => 'Fungsional', 'kelas_jabatan' => 9, 'kebutuhan' => 1, 'unit' => 'litbang'],
            ['nama_jabatan' => 'JF Pranata Komputer Ahli Pertama', 'jenis_jabatan' => 'Fungsional', 'kelas_jabatan' => 8, 'kebutuhan' => 1, 'unit' => 'litbang'],
            ['nama_jabatan' => 'JF Analis Data Ilmiah Ahli Pertama', 'jenis_jabatan' => 'Fungsional', 'kelas_jabatan' => 8, 'kebutuhan' => 1, 'unit' => 'litbang'],
            ['nama_jabatan' => 'JF Penata Penerbitan Ilmiah Ahli Pertama', 'jenis_jabatan' => 'Fungsional', 'kelas_jabatan' => 8, 'kebutuhan' => 1, 'unit' => 'litbang'],
            ['nama_jabatan' => 'Pengolah Data dan Informasi', 'jenis_jabatan' => 'Pelaksana', 'kelas_jabatan' => 6, 'kebutuhan' => 1, 'unit' => 'litbang'],
        ];

        foreach ($defaults as $j) {
            Jabatan::firstOrCreate(
                [
                    'nama_jabatan' => $j['nama_jabatan'],
                    'unit_kerja' => $j['unit'],
                ],
                [
                    'jenis_jabatan' => $j['jenis_jabatan'],
                    'kelas_jabatan' => $j['kelas_jabatan'],
                    'kebutuhan' => $j['kebutuhan'],
                ]
            );
        }
    }
}
