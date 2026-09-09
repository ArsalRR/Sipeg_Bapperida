<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Pegawai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class UserController extends Controller
{
    public function index(): View
    {
        $users = User::with('pegawai')->latest()->get();
        // Ambil pegawai yang belum memiliki user_id untuk dropdown
        $pegawais = Pegawai::whereNull('user_id')->get();
        
        return view('admin.users.index', compact('users', 'pegawais'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'username' => ['required', 'string', 'max:255', 'unique:users,username', 'regex:/^\S+$/'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
            'role' => ['required', Rule::in(['superadmin', 'admin', 'user'])],
            'pegawai_id' => ['nullable', 'exists:pegawais,id'],
        ]);

        DB::transaction(function () use ($validated) {
            $user = User::create([
                'username' => $validated['username'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role' => $validated['role'],
            ]);

            if (!empty($validated['pegawai_id'])) {
                Pegawai::where('id', $validated['pegawai_id'])->update(['user_id' => $user->id]);
            }
        });

        return redirect()->route('admin.users.index')->with('success', 'User berhasil dibuat!');
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'username' => ['required', 'string', 'max:255', Rule::unique('users')->ignore($user->id), 'regex:/^\S+$/'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => ['nullable', 'string', 'min:8'],
            'role' => ['required', Rule::in(['superadmin', 'admin', 'user'])],
            'pegawai_id' => ['nullable', 'exists:pegawais,id'],
        ]);

        DB::transaction(function () use ($validated, $user) {
            $user->username = $validated['username'];
            $user->email = $validated['email'];
            $user->role = $validated['role'];

            if (!empty($validated['password'])) {
                $user->password = Hash::make($validated['password']);
            }
            $user->save();

            $newPegawaiId = $validated['pegawai_id'] ?? null;
            $oldPegawai = $user->pegawai;

            // Jika dihubungkan ke Pegawai baru yang terdaftar di master pegawai
            if ($newPegawaiId && (!$oldPegawai || $oldPegawai->id != $newPegawaiId)) {
                // Jika user sebelumnya punya record pegawai dummy buatan register (misal NIP nya kosong), hapus record dummynya
                if ($oldPegawai && empty($oldPegawai->nip)) {
                    $oldPegawai->forceDelete();
                } elseif ($oldPegawai) {
                    $oldPegawai->update(['user_id' => null]);
                }

                // Hubungkan user ke pegawai pilihan admin
                Pegawai::where('id', $newPegawaiId)->update(['user_id' => $user->id]);
            }
        });

        return redirect()->route('admin.users.index')->with('success', 'Perubahan data user berhasil disimpan!');
    }

    public function destroy(User $user): RedirectResponse
    {
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.users.index')->with('error', 'Anda tidak dapat menghapus akun Anda sendiri!');
        }

        DB::transaction(function () use ($user) {
            if ($user->pegawai) {
                $user->pegawai->delete(); 
            }
            $user->delete();
        });

        return redirect()->route('admin.users.index')->with('success', 'User dan Data Pegawai telah dihapus.');
    }

    public function activate(User $user): RedirectResponse
    {
        $user->update(['is_active' => true]);

        return redirect()->route('admin.users.index')->with('success', "Akun {$user->username} telah disetujui dan diaktifkan!");
    }
}
