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
        $historyRelations = ['histories.jabatan', 'histories.user'];
        if (\Illuminate\Support\Facades\Schema::hasColumn('history_pegawais', 'bidang_id')) {
            $historyRelations[] = 'histories.bidang';
        }

        $pegawais = Pegawai::with(array_merge(['jabatan', 'bidang', 'user'], $historyRelations))->latest()->get();
        $jabatans = Jabatan::withCount(['pegawais' => function($q) {
            $q->where('status_kerja', 'Aktif')->orWhereNull('status_kerja');
        }])->get();
        $bidangs = \Illuminate\Support\Facades\Schema::hasTable('bidangs') 
            ? \App\Models\Bidang::orderBy('id', 'asc')->get() 
            : collect();
        $users = User::whereDoesntHave('pegawai')->get();
        
        return view('admin.pegawai.index', compact('pegawais', 'jabatans', 'bidangs', 'users'));
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
            'bidang_id'  => ['required', 'exists:bidangs,id'],
            'golongan' => ['nullable', 'string', 'max:50'],
            'status_pernikahan' => ['required', Rule::in(['Lajang', 'Menikah', 'Cerai Hidup', 'Cerai Mati'])],
            'status_kerja' => ['required', Rule::in(['Aktif', 'Tidak Aktif'])],
            'user_id' => ['nullable', 'exists:users,id', 'unique:pegawais,user_id'],
            'tanggal_berlaku' => ['nullable', 'date'],
            'foto' => ['nullable', 'image', 'max:2048'],
        ]);

        if (empty($validated['tanggal_berlaku'])) {
            $validated['tanggal_berlaku'] = now()->toDateString();
        }

        if ($request->filled('cropped_foto')) {
            $imageParts = explode(";base64,", $request->input('cropped_foto'));
            if (count($imageParts) == 2) {
                $imageDecoded = base64_decode($imageParts[1]);
                $filename = 'pegawai/foto/' . uniqid() . '.jpg';
                Storage::disk('public')->put($filename, $imageDecoded);
                $validated['foto'] = $filename;
            }
        } elseif ($request->hasFile('foto')) {
            $validated['foto'] = $request->file('foto')->store('pegawai/foto', 'public');
        }

        // Cek kapasitas jabatan
        $jabatan = Jabatan::find($validated['jabatan_id']);
        if ($jabatan) {
            $maxCapacity = $jabatan->kebutuhan ?? $jabatan->jumlah;
            if ($maxCapacity !== null && $maxCapacity > 0) {
                $currentActiveCount = $jabatan->pegawais()->where(function($q) {
                    $q->where('status_kerja', 'Aktif')->orWhereNull('status_kerja');
                })->count();

                if ($currentActiveCount >= $maxCapacity) {
                    return back()->withInput()->with('error', "Jabatan {$jabatan->nama_jabatan} sudah terisi penuh ({$currentActiveCount}/{$maxCapacity} formasi).");
                }
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
            'bidang_id'  => ['required', 'exists:bidangs,id'],
            'golongan' => ['nullable', 'string', 'max:50'],
            'status_pernikahan' => ['required', Rule::in(['Lajang', 'Menikah', 'Cerai Hidup', 'Cerai Mati'])],
            'status_kerja' => ['required', Rule::in(['Aktif', 'Tidak Aktif'])],
            'user_id' => ['nullable', 'exists:users,id', Rule::unique('pegawais')->ignore($pegawai->id)],
            'tanggal_berlaku' => ['nullable', 'date'],
            'foto' => ['nullable', 'image', 'max:2048'],
        ]);

        if (empty($validated['tanggal_berlaku'])) {
            $validated['tanggal_berlaku'] = now()->toDateString();
        }

        if ($request->filled('cropped_foto')) {
            if ($pegawai->foto) {
                Storage::disk('public')->delete($pegawai->foto);
            }
            $imageParts = explode(";base64,", $request->input('cropped_foto'));
            if (count($imageParts) == 2) {
                $imageDecoded = base64_decode($imageParts[1]);
                $filename = 'pegawai/foto/' . uniqid() . '.jpg';
                Storage::disk('public')->put($filename, $imageDecoded);
                $validated['foto'] = $filename;
            }
        } elseif ($request->hasFile('foto')) {
            if ($pegawai->foto) {
                Storage::disk('public')->delete($pegawai->foto);
            }
            $validated['foto'] = $request->file('foto')->store('pegawai/foto', 'public');
        }

        // Cek kapasitas jabatan (jika ganti jabatan ATAU mengaktifkan pegawai yang tadinya Tidak Aktif)
        $isActivating = ($validated['status_kerja'] === 'Aktif' && $pegawai->status_kerja === 'Tidak Aktif');
        $isChangingJabatan = ($validated['jabatan_id'] != $pegawai->jabatan_id);

        if (($isChangingJabatan || $isActivating) && $validated['status_kerja'] === 'Aktif') {
            $jabatan = Jabatan::find($validated['jabatan_id']);
            if ($jabatan) {
                $maxCapacity = $jabatan->kebutuhan ?? $jabatan->jumlah;
                if ($maxCapacity !== null && $maxCapacity > 0) {
                    $currentActiveCount = $jabatan->pegawais()->where('id', '!=', $pegawai->id)->where(function($q) {
                        $q->where('status_kerja', 'Aktif')->orWhereNull('status_kerja');
                    })->count();

                    if ($currentActiveCount >= $maxCapacity) {
                        return back()->withInput()->with('error', "Jabatan {$jabatan->nama_jabatan} sudah terisi penuh oleh pegawai aktif lain ({$currentActiveCount}/{$maxCapacity} formasi). Pegawai tidak dapat diaktifkan pada jabatan ini.");
                    }
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
