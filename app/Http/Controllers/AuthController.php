<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AuthController extends Controller
{
    private function messages(): array
    {
        return [
            'required' => ':attribute wajib diisi.',
            'string' => ':attribute harus berupa teks.',
            'numeric' => ':attribute harus berupa angka.',
            'email' => ':attribute harus berupa alamat email yang valid.',
            'max' => ':attribute maksimal :max karakter.',
            'min' => ':attribute minimal :min karakter.',
            'unique' => ':attribute sudah digunakan.',
            'confirmed' => 'Konfirmasi :attribute tidak cocok.',
            'captcha' => ':attribute salah.',
            'username.regex' => 'Username tidak boleh mengandung spasi.',
            'login.regex' => 'Username atau email tidak boleh mengandung spasi.',
        ];
    }

    private function attributes(): array
    {
        return [
            'login' => 'Username atau email',
            'username' => 'Username',
            'email' => 'Email',
            'password' => 'Kata sandi',
            'captcha' => 'Kode captcha',
        ];
    }

    public function showLogin(): View
    {
        return view('auth.login');
    }

    public function processLogin(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'login' => ['required', 'string', 'regex:/^\S+$/'],
            'password' => ['required'],
            'captcha' => ['required', 'captcha'],
        ], $this->messages(), $this->attributes());

        $loginType = filter_var($credentials['login'], FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        if (Auth::validate([$loginType => $credentials['login'], 'password' => $credentials['password']])) {
            $user = User::where($loginType, $credentials['login'])->first();

            if (!$user->is_active) {
                return back()->withErrors([
                    'login' => 'Akun Anda belum aktif. Silakan hubungi superadmin untuk aktivasi.',
                ])->onlyInput('login');
            }

            Auth::login($user);
            $request->session()->regenerate();

            return redirect()->route('welcome');
        }

        return back()->withErrors([
            'login' => 'Kredensial yang diberikan tidak cocok dengan catatan kami.',
        ])->onlyInput('login');
    }

    public function showRegister(): View
    {
        return view('auth.register');
    }

    public function processRegister(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'username' => ['required', 'string', 'max:255', 'unique:users,username', 'regex:/^\S+$/'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], $this->messages(), $this->attributes());

        User::create([
            'username' => $validated['username'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'user',
            'is_active' => false,
        ]);

        return redirect()->route('login')->with('success', 'Pendaftaran berhasil! Akun Anda menunggu persetujuan superadmin. Silakan hubungi admin untuk aktivasi.');
    }

    public function logout(Request $request): RedirectResponse
    {
        $request->user()?->tokens()->delete();

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}