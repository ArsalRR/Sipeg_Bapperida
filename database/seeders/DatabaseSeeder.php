<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Jabatan;
use App\Models\Pegawai;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(JabatanSeeder::class);

        $user = User::create([
            'username' => 'superadmin',
            'email' => 'superadmin@simpeg.local',
            'password' => Hash::make('katasandi123'),
            'role' => 'superadmin',
            'is_active' => true,
        ]);

        $jabatan = Jabatan::first();

        Pegawai::create([
            'user_id' => $user->id,
            'nama' => 'Super Administrator',
            'gelar_depan' => null,
            'gelar_belakang' => 'S.Kom., M.T.',
            'nip' => '199001012024011001',
            'nik' => '3171234567890123',
            'alamat' => 'Jl. Kebon Sirih No. 1, Jakarta Pusat',
            'tempat_lahir' => 'Jakarta',
            'tanggal_lahir' => '1990-01-01',
            'jenis_kelamin' => 'Laki-laki',
            'agama' => 'Islam',
            'status_kepegawaian' => 'PNS',
            'jabatan_id' => $jabatan->id ?? 1,
            'golongan' => 'III/c',
            'status_pernikahan' => 'Menikah',
            'foto' => null,
        ]);
    }
}
