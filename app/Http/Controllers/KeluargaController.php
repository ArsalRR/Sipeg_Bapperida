<?php

namespace App\Http\Controllers;

use App\Models\Keluarga;
use App\Models\Pegawai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KeluargaController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if (in_array($user->role, ['admin', 'superadmin'])) {
            // Ambil pegawai beserta data keluarganya
            $pegawais = Pegawai::with(['keluargas', 'jabatan'])->orderBy('nama', 'asc')->get();
            $keluargas = Keluarga::with('pegawai')->latest()->get();
        } else {
            // User biasa hanya melihat & mengelola data keluarganya sendiri
            $pegawai = $user->pegawai ? $user->pegawai->load(['keluargas', 'jabatan']) : null;
            $keluargas = $pegawai ? $pegawai->keluargas()->with('pegawai')->latest()->get() : collect();
            $pegawais = $pegawai ? collect([$pegawai]) : collect();
        }

        return view('keluarga.index', compact('keluargas', 'pegawais'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        $rules = [
            'nama' => 'required|string|max:255',
            'hubungan' => 'required|string|max:255',
            'pekerjaan' => 'required|string|max:255',
            'tempat_lahir' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'tanggal_perkawinan' => 'nullable|date',
            'tunjangan' => 'required|in:Dapat,Tidak Dapat',
        ];

        if (in_array($user->role, ['admin', 'superadmin'])) {
            $rules['pegawai_id'] = 'required|exists:pegawais,id';
        }

        $validated = $request->validate($rules);

        // Jika hubungan bukan Suami atau Istri, kosongkan tanggal_perkawinan
        if (!in_array($validated['hubungan'], ['Suami', 'Istri'])) {
            $validated['tanggal_perkawinan'] = null;
        }

        if (!in_array($user->role, ['admin', 'superadmin'])) {
            if (!$user->pegawai) {
                return redirect()->back()->with('error', 'Profil Pegawai Anda belum terhubung.');
            }
            $validated['pegawai_id'] = $user->pegawai->id;
        }

        Keluarga::create($validated);

        return redirect()->route('keluarga.index')->with('success', 'Data keluarga berhasil ditambahkan.');
    }

    public function update(Request $request, Keluarga $keluarga)
    {
        $user = Auth::user();

        // Otorisasi: jika user biasa, pastikan milik pegawainya sendiri
        if (!in_array($user->role, ['admin', 'superadmin'])) {
            if (!$user->pegawai || $keluarga->pegawai_id !== $user->pegawai->id) {
                abort(403, 'Akses tidak diizinkan.');
            }
        }

        $rules = [
            'nama' => 'required|string|max:255',
            'hubungan' => 'required|string|max:255',
            'pekerjaan' => 'required|string|max:255',
            'tempat_lahir' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'tanggal_perkawinan' => 'nullable|date',
            'tunjangan' => 'required|in:Dapat,Tidak Dapat',
        ];

        if (in_array($user->role, ['admin', 'superadmin'])) {
            $rules['pegawai_id'] = 'required|exists:pegawais,id';
        }

        $validated = $request->validate($rules);

        if (!in_array($validated['hubungan'], ['Suami', 'Istri'])) {
            $validated['tanggal_perkawinan'] = null;
        }

        $keluarga->update($validated);

        return redirect()->route('keluarga.index')->with('success', 'Data keluarga berhasil diperbarui.');
    }

    public function destroy(Keluarga $keluarga)
    {
        $user = Auth::user();

        if (!in_array($user->role, ['admin', 'superadmin'])) {
            if (!$user->pegawai || $keluarga->pegawai_id !== $user->pegawai->id) {
                abort(403, 'Akses tidak diizinkan.');
            }
        }

        $keluarga->delete();

        return redirect()->route('keluarga.index')->with('success', 'Data keluarga berhasil dihapus.');
    }
}
