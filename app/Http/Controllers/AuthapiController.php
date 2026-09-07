<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthapiController extends Controller
{
    public function login(Request $request)
    {
        $loginUserData = $request->validate([
            'login' => 'required|string',
            'password' => 'required|string|min:8',
        ]);

        $login = $loginUserData['login'];
        $password = $loginUserData['password'];
        $user = User::where('username', $login)
                    ->orWhere('email', $login)
                    ->first();
        if (!$user) {
            return response()->json([
                'message' => 'Kredensial Tidak Valid',
                'error' => 'user_not_found'
            ], 401);
        }
        if (!Hash::check($password, $user->password)) {
            return response()->json([
                'message' => 'Kredensial Tidak Valid',
                'error' => 'invalid_password'
            ], 401);
        }
        if (!$user->is_active) {
            return response()->json([
                'message' => 'Akun Anda belum aktif. Silakan hubungi admin untuk aktivasi.',
                'error' => 'account_inactive'
            ], 403);
        }

        $token = $user->createToken('AuthToken')->plainTextToken;

        return response()->json([
            'message' => 'Login berhasil',
            'token' => $token,
            'user' => $user,
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        
        return response()->json([
            'message' => 'Logout Success',
            'success' => 200
        ]);
    }
}