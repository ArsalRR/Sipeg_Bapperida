<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class SsoController extends Controller
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

        $user = User::find($userId);

        if (!$user || !$user->is_active) {
            return response()->json(['message' => 'Akun tidak aktif'], 403);
        }

        $token = $user->createToken(
            'sso',
            ['*'],
            now()->addSeconds(config('sso.token_ttl', 28800))
        )->plainTextToken;

        return response()->json([
            'token' => $token,
            'user'  => [
                'id'       => $user->id,
                'email'    => $user->email,
                'username' => $user->username,
                'role'     => $user->role,
            ],
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