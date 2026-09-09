<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Pegawai;
use App\Models\User;
use App\Models\Jabatan;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();

        if (in_array($user->role, ['admin', 'superadmin'])) {
            $totalPegawai = Pegawai::count();
            $totalUser = User::count();
            $totalJabatan = Jabatan::count();
            $totalMenunggu = User::where('is_active', false)->count();
            
            return view('dashboard', compact('totalPegawai', 'totalUser', 'totalJabatan', 'totalMenunggu'));
        }

        // Tampilan khusus User Biasa
        $pegawai = $user->pegawai;
        $totalKeluarga = $pegawai ? $pegawai->keluargas()->count() : 0;
        
        return view('dashboard', compact('pegawai', 'totalKeluarga'));
    }
}

