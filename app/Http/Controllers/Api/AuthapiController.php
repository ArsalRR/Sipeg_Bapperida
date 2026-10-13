<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\JsonResponse;

class AuthApiController extends Controller
{
    public function login(Request $request): JsonResponse
    {
        $data = $request->validate([
            'login'    => 'required|string',
            'password' => 'required|string',
        ]);

        $login = trim($data['login']);

        $user = User::with('pegawai.jabatan')
            ->where('username', $login)
            ->orWhere('email', $login)
            ->first();

        if (!$user || !Hash::check($data['password'], $user->password)) {
            return response()->json([
                'message' => 'Kredensial Tidak Valid',
                'error'   => 'invalid_credentials',
            ], 401);
        }

        if (!$user->is_active) {
            return response()->json([
                'message' => 'Akun Anda belum aktif. Silakan hubungi admin untuk aktivasi.',
                'error'   => 'account_inactive',
            ], 403);
        }

        $token = $user->createToken('AuthToken')->plainTextToken;

        return response()->json([
            'message' => 'Login berhasil',
            'token'   => $token,
            'user'    => [
                'id'           => $user->id,
                'username'     => $user->username,
                'email'        => $user->email,
                'role'         => $user->role,
                'is_active'    => $user->is_active,
                'nama_pegawai' => $user->pegawai?->nama,
                'jabatan'      => $user->pegawai?->jabatan?->nama_jabatan,
            ],
        ]);
    }

    public function logout(Request $request)
    {
        $user = $request->user();

        if (!$user) {
            return response()->json([
                'message' => 'Unauthenticated',
            ], 401);
        }

        $token = $user->currentAccessToken();

        if ($token) {
            $token->delete();
        }

        return response()->json([
            'message' => 'Logout Success',
            'success' => true,
        ]);
    }
    public function profile(Request $request): JsonResponse
    {
        $user = $request->user()->load('pegawai.jabatan');

        return response()->json([
            'user' => [
                'id'           => $user->id,
                'username'     => $user->username,
                'email'        => $user->email,
                'role'         => $user->role,
                'is_active'    => $user->is_active,
                'nama_pegawai' => $user->pegawai?->nama,
                'jabatan'      => $user->pegawai?->jabatan?->nama_jabatan,
            ],
        ]);
    }
}