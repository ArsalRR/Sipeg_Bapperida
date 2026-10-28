<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pegawai;
use Illuminate\Http\Request;

class PegawaiApiController extends Controller
{
    /**
     * GET /api/pegawai
     * List semua pegawai beserta relasi jabatan, bidang, dan user.
     */
    public function index(Request $request)
    {
        $query = Pegawai::with(['jabatan', 'bidang', 'user'])
            ->latest();

        // Filter opsional: status_kerja (contoh: ?status_kerja=Aktif)
        if ($request->has('status_kerja')) {
            $query->where('status_kerja', $request->input('status_kerja'));
        }

        // Filter opsional: bidang_id
        if ($request->has('bidang_id')) {
            $query->where('bidang_id', $request->input('bidang_id'));
        }

        $pegawai = $query->get()->map(fn($p) => $this->formatPegawai($p));

        return response()->json([
            'data'  => $pegawai,
            'total' => $pegawai->count(),
        ]);
    }

    /**
     * GET /api/pegawai/{id}
     * Detail satu pegawai by ID.
     */
    public function show($id)
    {
        $pegawai = Pegawai::with(['jabatan', 'bidang', 'user'])
            ->findOrFail($id);

        return response()->json([
            'data' => $this->formatPegawai($pegawai),
        ]);
    }

    /**
     * GET /api/pegawai/by-nip/{nip}
     * Cari pegawai berdasarkan NIP.
     */
    public function byNip($nip)
    {
        $pegawai = Pegawai::with(['jabatan', 'bidang', 'user'])
            ->where('nip', $nip)
            ->firstOrFail();

        return response()->json([
            'data' => $this->formatPegawai($pegawai),
        ]);
    }

    /**
     * GET /api/sso/master-pegawai
     * Endpoint untuk sistem terintegrasi (KAWAL, SURYA, JAGA) menarik semua pegawai aktif.
     */
    public function masterData(Request $request)
    {
        $pegawai = Pegawai::with(['jabatan', 'bidang', 'user'])
            ->get()
            ->map(fn($p) => $this->formatPegawai($p));

        return response()->json([
            'status' => 'success',
            'data'   => $pegawai,
            'total'  => $pegawai->count(),
        ]);
    }

    /**
     * Format output pegawai agar konsisten di semua endpoint.
     */
    private function formatPegawai(Pegawai $pegawai): array
    {
        return [
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
            'tanggal_berlaku'    => $pegawai->tanggal_berlaku?->format('Y-m-d'),
            'foto'               => $pegawai->foto
                ? asset('storage/' . $pegawai->foto)
                : null,
            'jabatan' => $pegawai->jabatan ? [
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
            'user' => $pegawai->user ? [
                'id'       => $pegawai->user->id,
                'username' => $pegawai->user->username,
                'email'    => $pegawai->user->email,
                'role'     => $pegawai->user->role,
            ] : null,
            'created_at' => $pegawai->created_at,
            'updated_at' => $pegawai->updated_at,
        ];
    }
}
