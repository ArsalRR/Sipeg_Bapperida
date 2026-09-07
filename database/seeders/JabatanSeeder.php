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
        $jabatans = [
            // JABATAN STRUKTURAL (JPT Pratama, Administrator, Pengawas)
            ['nama_jabatan' => 'Kepala Badan Perencanaan Pembangunan, Riset, dan Inovasi Daerah', 'jenis_jabatan' => 'Struktural'],
            ['nama_jabatan' => 'Sekretaris Badan Perencanaan Pembangunan, Riset, dan Inovasi Daerah', 'jenis_jabatan' => 'Struktural'],
            ['nama_jabatan' => 'Kepala Bidang Perekonomian, Sumber Daya Alam, Infrastruktur dan Kewilayahan', 'jenis_jabatan' => 'Struktural'],
            ['nama_jabatan' => 'Kepala Bidang Pemerintahan dan Pembangunan Manusia', 'jenis_jabatan' => 'Struktural'],
            ['nama_jabatan' => 'Kepala Bidang Perencanaan, Pengendalian dan Evaluasi Pembangunan Daerah', 'jenis_jabatan' => 'Struktural'],
            ['nama_jabatan' => 'Kepala Bidang Riset dan Inovasi Daerah', 'jenis_jabatan' => 'Struktural'],
            ['nama_jabatan' => 'Kepala Sub Bagian Umum dan Kepegawaian', 'jenis_jabatan' => 'Struktural'],
            ['nama_jabatan' => 'Kepala Sub Bagian Perencanaan Evaluasi dan Keuangan', 'jenis_jabatan' => 'Struktural'],
        
            // JABATAN FUNGSIONAL
            ['nama_jabatan' => 'JF Perencana Ahli Madya', 'jenis_jabatan' => 'Fungsional'],
            ['nama_jabatan' => 'JF Perencana Ahli Muda', 'jenis_jabatan' => 'Fungsional'],
            ['nama_jabatan' => 'JF Perencana Ahli Pertama', 'jenis_jabatan' => 'Fungsional'],
            ['nama_jabatan' => 'JF Peneliti Ahli Muda', 'jenis_jabatan' => 'Fungsional'],
            ['nama_jabatan' => 'JF Peneliti Ahli Pertama', 'jenis_jabatan' => 'Fungsional'],
            ['nama_jabatan' => 'JF Pranata Komputer Ahli Muda', 'jenis_jabatan' => 'Fungsional'],
            ['nama_jabatan' => 'JF Pranata Komputer Ahli Pertama', 'jenis_jabatan' => 'Fungsional'],
            ['nama_jabatan' => 'JF Analis Data Ilmiah Ahli Pertama', 'jenis_jabatan' => 'Fungsional'],
            ['nama_jabatan' => 'JF Penata Penerbitan Ilmiah Ahli Pertama', 'jenis_jabatan' => 'Fungsional'],
            ['nama_jabatan' => 'JF Arsiparis Pelaksana', 'jenis_jabatan' => 'Fungsional'],
        
            // JABATAN PELAKSANA
            ['nama_jabatan' => 'Penelaah Teknis Kebijakan', 'jenis_jabatan' => 'Pelaksana'],
            ['nama_jabatan' => 'Pengolah Data dan Informasi', 'jenis_jabatan' => 'Pelaksana'],
            ['nama_jabatan' => 'Pengadministrasi Perkantoran', 'jenis_jabatan' => 'Pelaksana'],
        ];

        foreach ($jabatans as $jabatan) {
            Jabatan::updateOrCreate(
                ['nama_jabatan' => $jabatan['nama_jabatan']],
                ['jenis_jabatan' => $jabatan['jenis_jabatan']]
            );
        }
    }
}
