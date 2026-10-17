<?php

namespace App\Http\Controllers;

class WelcomeController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $profile = null;

        if ($user) {
            $user->loadMissing('pegawai.jabatan');

            $profile = [
                'nama'    => $user->pegawai?->nama ?? $user->username,
                'jabatan' => $user->pegawai?->jabatan?->nama_jabatan,
            ];
        }

        return view('ssopage', compact('profile'));
    }
}