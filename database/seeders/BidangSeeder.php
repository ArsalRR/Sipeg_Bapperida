<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Bidang;

class BidangSeeder extends Seeder
{
    public function run(): void
    {
        $bidangs = [
            [
                'nama_bidang' => 'Sekretariat',
                'singkatan'   => 'sekretariat',
            ],
            [
                'nama_bidang' => 'Subbagian Umum dan Kepegawaian',
                'singkatan'   => 'umum',
            ],
            [
                'nama_bidang' => 'Subbagian Perencanaan, Evaluasi dan Keuangan',
                'singkatan'   => 'perencanaan_evaluasi',
            ],
            [
                'nama_bidang' => 'Bidang Pemerintahan dan Pembangunan Manusia',
                'singkatan'   => 'ppm',
            ],
            [
                'nama_bidang' => 'Bidang Perekonomian, SDA, Infrastruktur dan Kewilayahan',
                'singkatan'   => 'ekonomi',
            ],
            [
                'nama_bidang' => 'Bidang Perencanaan, Pengendalian dan Evaluasi Pembangunan Daerah',
                'singkatan'   => 'ppepd',
            ],
            [
                'nama_bidang' => 'Bidang Penelitian dan Pengembangan',
                'singkatan'   => 'litbang',
            ],
        ];

        foreach ($bidangs as $b) {
            Bidang::updateOrCreate(
                ['singkatan' => $b['singkatan']],
                ['nama_bidang' => $b['nama_bidang']]
            );
        }
    }
}
