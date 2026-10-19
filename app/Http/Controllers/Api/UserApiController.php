<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserApiController extends Controller
{
    /**
     * GET /api/users
     * List semua user aktif beserta data pegawai.
     */
    public function index(Request $request)
    {
        $users = User::with(['pegawai.jabatan', 'pegawai.bidang'])
            ->where('is_active', true)
            ->get()
            ->map(fn($user) => $this->formatUser($user));

        return response()->json([
            'data'  => $users,
            'total' => $users->count(),
        ]);
    }

    /**
     * GET /api/user/{id}
     * Detail satu user by ID beserta data pegawai.
     */
    public function show($id)
    {
        $user = User::with(['pegawai.jabatan', 'pegawai.bidang'])
            ->findOrFail($id);

        return response()->json([
            'data' => $this->formatUser($user),
        ]);
    }

    /**
     * GET /api/user/by-username/{username}
     * Cari user by username beserta data pegawai.
     * Berguna untuk SSO — setelah dapat username dari token, ambil profil lengkap.
     */
    public function byUsername($username)
    {
        $user = User::with(['pegawai.jabatan', 'pegawai.bidang'])
            ->where('username', $username)
            ->firstOrFail();

        return response()->json([
            'data' => $this->formatUser($user),
        ]);
    }

    /**
     * Format output user agar konsisten dan aman (password tidak ikut).
     */
    private function formatUser(User $user): array
    {
        $pegawai = $user->pegawai;

        return [
            // Data User (akun)
            'id'         => $user->id,
            'username'   => $user->username,
            'email'      => $user->email,
            'role'       => $user->role,
            'is_active'  => (bool) $user->is_active,
            'created_at' => $user->created_at,
            'updated_at' => $user->updated_at,

            // Data Pegawai (profil)
            'pegawai' => $pegawai ? [
                'id'                 => $pegawai->id,
                'nama'               => $pegawai->nama,
                'nama_lengkap'       => $pegawai->nama_lengkap,
                'gelar_depan'        => $pegawai->gelar_depan,
                'gelar_belakang'     => $pegawai->gelar_belakang,
                'nip'                => $pegawai->nip,
                'nik'                => $pegawai->nik,
                'tempat_lahir'       => $pegawai->tempat_lahir,
                'tanggal_lahir'      => $pegawai->tanggal_lahir?->format('Y-m-d'),
                'jenis_kelamin'      => $pegawai->jenis_kelamin,
                'agama'              => $pegawai->agama,
                'alamat'             => $pegawai->alamat,
                'status_kepegawaian' => $pegawai->status_kepegawaian,
                'golongan'           => $pegawai->golongan,
                'golongan_pangkat'   => $pegawai->golongan_pangkat,
                'mkg'                => $pegawai->mkg,
                'status_pernikahan'  => $pegawai->status_pernikahan,
                'status_kerja'       => $pegawai->status_kerja,
                'foto'               => $pegawai->foto
                    ? asset('storage/' . $pegawai->foto)
                    : null,
                'jabatan'  => $pegawai->jabatan ? [
                    'id'            => $pegawai->jabatan->id,
                    'nama_jabatan'  => $pegawai->jabatan->nama_jabatan,
                    'jenis_jabatan' => $pegawai->jabatan->jenis_jabatan,
                    'kelas_jabatan' => $pegawai->jabatan->kelas_jabatan,
                ] : null,
                'bidang' => $pegawai->bidang ? [
                    'id'          => $pegawai->bidang->id,
                    'nama_bidang' => $pegawai->bidang->nama_bidang,
                    'singkatan'   => $pegawai->bidang->singkatan,
                ] : null,
            ] : null,
        ];
    }
}
