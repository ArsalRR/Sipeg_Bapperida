<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Pegawai;
use App\Models\Jabatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\Rule;

use Illuminate\Support\Facades\Schema;
use App\Models\Bidang;

class ProfileController extends Controller
{
    public function index(): View
    {
        $user = Auth::user()->load('pegawai');
        $jabatans = Jabatan::withCount(['pegawais' => function($q) {
            $q->where('status_kerja', 'Aktif')->orWhereNull('status_kerja');
        }])->get();

        $bidangs = Schema::hasTable('bidangs') 
            ? Bidang::orderBy('nama_bidang')->get() 
            : collect([]);

        return view('admin.profile.index', compact('user', 'jabatans', 'bidangs'));
    }

    public function update(Request $request): RedirectResponse
    {
        $user = Auth::user();
        $pegawai = $user->pegawai;

        $validated = $request->validate([
            // User Fields
            'username' => ['required', 'string', 'max:255', Rule::unique('users')->ignore($user->id), 'regex:/^\S+$/'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            
            // Password Fields (Conditional)
            'current_password' => ['nullable', 'required_with:password', 'current_password'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],

            // Pegawai Fields
            'nama' => ['required', 'string', 'max:255'],
            'gelar_depan' => ['nullable', 'string', 'max:50'],
            'gelar_belakang' => ['nullable', 'string', 'max:50'],
            'nip' => ['nullable', 'string', 'max:30', Rule::unique('pegawais')->ignore($pegawai->id ?? 0)],
            'nik' => ['nullable', 'string', 'max:30', Rule::unique('pegawais')->ignore($pegawai->id ?? 0)],
            'alamat' => ['nullable', 'string'],
            'tempat_lahir' => ['nullable', 'string', 'max:100'],
            'tanggal_lahir' => ['nullable', 'date'],
            'jenis_kelamin' => ['nullable', Rule::in(['Laki-laki', 'Perempuan'])],
            'agama' => ['nullable', 'string', 'max:50'],
            'status_kepegawaian' => ['nullable', Rule::in(['PNS', 'PPPK', 'CPNS', 'PPPK Paruh Waktu', 'Non ASN'])],
            'bidang_id' => ['nullable', 'exists:bidangs,id'],
            'jabatan_id' => ['nullable', 'exists:jabatans,id'],
            'golongan' => ['nullable', 'string', 'max:50'],
            'status_pernikahan' => ['nullable', Rule::in(['Lajang', 'Menikah', 'Cerai Hidup', 'Cerai Mati'])],
            'foto' => ['nullable', 'image', 'max:2048'],
        ], [
            'username.regex' => 'Username tidak boleh mengandung spasi.',
            'current_password.required_with' => 'Password lama wajib diisi jika ingin mengubah password.'
        ]);

        DB::transaction(function () use ($request, $user, $pegawai, $validated) {
            // Update User
            $userData = [
                'username' => $validated['username'],
                'email' => $validated['email'],
            ];

            if (!empty($validated['password'])) {
                $userData['password'] = Hash::make($validated['password']);
            }

            $user->update($userData);

            // Update Pegawai
            if ($pegawai) {
                $pegawaiData = [
                    'nama' => $validated['nama'],
                    'gelar_depan' => $validated['gelar_depan'],
                    'gelar_belakang' => $validated['gelar_belakang'],
                    'nip' => $validated['nip'],
                    'nik' => $validated['nik'],
                    'alamat' => $validated['alamat'],
                    'tempat_lahir' => $validated['tempat_lahir'],
                    'tanggal_lahir' => $validated['tanggal_lahir'],
                    'jenis_kelamin' => $validated['jenis_kelamin'],
                    'agama' => $validated['agama'],
                    'status_kepegawaian' => $validated['status_kepegawaian'],
                    'bidang_id' => $validated['bidang_id'] ?? null,
                    'jabatan_id' => $validated['jabatan_id'] ?? null,
                    'golongan' => $validated['golongan'],
                    'status_pernikahan' => $validated['status_pernikahan'],
                ];

                if ($request->hasFile('foto')) {
                    if ($pegawai->foto) {
                        Storage::disk('public')->delete($pegawai->foto);
                    }
                    $pegawaiData['foto'] = $request->file('foto')->store('pegawai/foto', 'public');
                }

                $pegawai->update($pegawaiData);
            }
        });

        return redirect()->back()->with('success', 'Profil berhasil diperbarui!');
    }
}
