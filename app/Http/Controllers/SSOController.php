<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class SSOController extends Controller
{
    public function authorize(Request $request)
    {
        $redirect = $request->query('redirect_uri');

        abort_unless(
            in_array($redirect, config('sso.allowed_redirects', []), true),
            400,
            'redirect_uri tidak diizinkan'
        );

        $user = $request->user();

        if (!$user->is_active) {
            abort(403, 'Akun belum aktif');
        }

        $code = Str::random(64);
        Cache::put('sso_code_' . $code, $user->id, now()->addSeconds(config('sso.code_ttl', 60)));

        return redirect()->away($redirect . '?code=' . $code);
    }

    public function token(Request $request)
    {
        $request->validate(['code' => 'required|string']);
        $userId = Cache::pull('sso_code_' . $request->input('code'));

        if (!$userId) {
            return response()->json(['message' => 'Kode tidak valid atau kedaluwarsa'], 401);
        }

        $user = User::with(['pegawai.jabatan', 'pegawai.bidang'])->find($userId);

        if (!$user || !$user->is_active) {
            return response()->json(['message' => 'Akun tidak aktif'], 403);
        }

        $token = $user->createToken(
            'sso',
            ['*'],
            now()->addSeconds(config('sso.token_ttl', 28800))
        )->plainTextToken;

        $pegawai = $user->pegawai;

        return response()->json([
            'token' => $token,
            'user'  => [
                'id'        => $user->id,
                'email'     => $user->email,
                'username'  => $user->username,
                'role'      => $user->role,
                'is_active' => (bool) $user->is_active,
            ],
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
                'foto'               => $pegawai->foto ? asset('storage/' . $pegawai->foto) : null,
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
            ] : null,
        ]);
    }
    public function logout(Request $request)
    {
        $request->user()?->tokens()->delete(); 

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}