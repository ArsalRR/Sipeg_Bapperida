<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Pegawai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class AuthController extends Controller
{
    public function showLogin(Request $request): View
    {
        // Generate Simple Math Captcha
        $num1 = random_int(1, 10);
        $num2 = random_int(1, 10);
        $operator = random_int(0, 1) === 1 ? '+' : '-';
        if ($operator === '-' && $num1 < $num2) {
            $temp = $num1;
            $num1 = $num2;
            $num2 = $temp;
        }
        
        $result = $operator === '+' ? ($num1 + $num2) : ($num1 - $num2);
        
        $request->session()->put('captcha_result', $result);
        $captchaQuestion = "Berapa hasil dari {$num1} {$operator} {$num2}?";

        return view('auth.login', compact('captchaQuestion'));
    }

    public function processLogin(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'login' => ['required', 'string', 'regex:/^\S+$/'],
            'password' => ['required'],
            'captcha' => ['required', 'numeric'],
        ], [
            'login.regex' => 'Username atau email tidak boleh mengandung spasi.'
        ]);

        $captchaResult = $request->session()->get('captcha_result');
        if ((int)$credentials['captcha'] !== $captchaResult) {
            return back()->withErrors([
                'captcha' => 'Jawaban captcha salah.',
            ])->onlyInput('login');
        }

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

            return redirect()->intended('/dashboard');
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
        ], [
            'username.regex' => 'Username tidak boleh mengandung spasi.'
        ]);

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
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
