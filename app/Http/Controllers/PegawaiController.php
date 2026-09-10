<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Pegawai;
use App\Models\Jabatan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class PegawaiController extends Controller
{
    public function index(): View
    {
        $pegawais = Pegawai::with(['jabatan', 'user', 'histories.jabatan', 'histories.user'])->latest()->get();
        $jabatans = Jabatan::withCount('pegawais')->get();
        $users = User::whereDoesntHave('pegawai')->get();
        
        return view('admin.pegawai.index', compact('pegawais', 'jabatans', 'users'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'gelar_depan' => ['nullable', 'string', 'max:50'],
            'gelar_belakang' => ['nullable', 'string', 'max:50'],
            'nip' => ['required', 'string', 'size:18', 'unique:pegawais,nip'],
            'nik' => ['required', 'string', 'size:16', 'unique:pegawais,nik'],
            'alamat' => ['required', 'string'],
            'tempat_lahir' => ['required', 'string', 'max:100'],
            'tanggal_lahir' => ['required', 'date'],
            'jenis_kelamin' => ['required', Rule::in(['Laki-laki', 'Perempuan'])],
            'agama' => ['required', 'string', 'max:50'],
            'status_kepegawaian' => ['required', Rule::in(['PNS', 'PPPK', 'CPNS', 'PPPK Paruh Waktu', 'Non ASN'])],
            'jabatan_id' => ['required', 'exists:jabatans,id'],
            'golongan' => ['nullable', 'string', 'max:50'],
            'status_pernikahan' => ['required', Rule::in(['Lajang', 'Menikah', 'Cerai Hidup', 'Cerai Mati'])],
            'user_id' => ['nullable', 'exists:users,id', 'unique:pegawais,user_id'],
            'tanggal_berlaku' => ['nullable', 'date'],
            'foto' => ['nullable', 'image', 'max:2048'],
        ]);

        if (empty($validated['tanggal_berlaku'])) {
            $validated['tanggal_berlaku'] = now()->toDateString();
        }

        if ($request->hasFile('foto')) {
            $validated['foto'] = $request->file('foto')->storage('pegawai/foto', 'public');
        }

        // Cek kapasitas jabatan
        $jabatan = Jabatan::find($validated['jabatan_id']);
        if ($jabatan && $jabatan->jumlah !== null) {
            if ($jabatan->pegawais()->count() >= $jabatan->jumlah) {
                return back()->withInput()->with('error', "Jabatan {$jabatan->nama_jabatan} sudah terisi penuh ({$jabatan->jumlah} orang).");
            }
        }

        Pegawai::create($validated);

        return redirect()->route('admin.pegawais.index')->with('success', 'Pegawai berhasil ditambahkan!');
    }

    public function update(Request $request, Pegawai $pegawai): RedirectResponse
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'gelar_depan' => ['nullable', 'string', 'max:50'],
            'gelar_belakang' => ['nullable', 'string', 'max:50'],
            'nip' => ['required', 'string', 'size:18', Rule::unique('pegawais')->ignore($pegawai->id)],
            'nik' => ['required', 'string', 'size:16', Rule::unique('pegawais')->ignore($pegawai->id)],
            'alamat' => ['required', 'string'],
            'tempat_lahir' => ['required', 'string', 'max:100'],
            'tanggal_lahir' => ['required', 'date'],
            'jenis_kelamin' => ['required', Rule::in(['Laki-laki', 'Perempuan'])],
            'agama' => ['required', 'string', 'max:50'],
            'status_kepegawaian' => ['required', Rule::in(['PNS', 'PPPK', 'CPNS', 'PPPK Paruh Waktu', 'Non ASN'])],
            'jabatan_id' => ['required', 'exists:jabatans,id'],
            'golongan' => ['nullable', 'string', 'max:50'],
            'status_pernikahan' => ['required', Rule::in(['Lajang', 'Menikah', 'Cerai Hidup', 'Cerai Mati'])],
            'user_id' => ['nullable', 'exists:users,id', Rule::unique('pegawais')->ignore($pegawai->id)],
            'tanggal_berlaku' => ['nullable', 'date'],
            'foto' => ['nullable', 'image', 'max:2048'],
        ]);

        if ($request->hasFile('foto')) {
            if ($pegawai->foto) {
                Storage::disk('public')->delete($pegawai->foto);
            }
            $validated['foto'] = $request->file('foto')->store('pegawai/foto', 'public');
        }

        // Cek kapasitas jabatan (kecualikan pegawai ini sendiri)
        if ($validated['jabatan_id'] != $pegawai->jabatan_id) {
            $jabatan = Jabatan::find($validated['jabatan_id']);
            if ($jabatan && $jabatan->jumlah !== null) {
                if ($jabatan->pegawais()->count() >= $jabatan->jumlah) {
                    return back()->withInput()->with('error', "Jabatan {$jabatan->nama_jabatan} sudah terisi penuh ({$jabatan->jumlah} orang).");
                }
            }
        }

        $pegawai->update($validated);

        return redirect()->route('admin.pegawais.index')->with('success', 'Data pegawai berhasil diperbarui!');
    }

    public function destroy(Pegawai $pegawai): RedirectResponse
    {
        if ($pegawai->foto) {
            Storage::disk('public')->delete($pegawai->foto);
        }
        
        $pegawai->delete();

        return redirect()->route('admin.pegawais.index')->with('success', 'Pegawai berhasil dihapus!');
    }
}
