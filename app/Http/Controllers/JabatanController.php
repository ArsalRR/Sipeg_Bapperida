<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Jabatan;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class JabatanController extends Controller
{
    public function index(): View
    {
        $jabatans = Jabatan::latest()->get();
        return view('admin.jabatan.index', compact('jabatans'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama_jabatan' => ['required', 'string', 'max:255'],
            'jenis_jabatan' => ['required', Rule::in(['Struktural', 'Fungsional', 'Pelaksana'])],
        ]);

        Jabatan::create($validated);

        return redirect()->route('admin.jabatans.index')->with('success', 'Jabatan berhasil ditambahkan!');
    }

    public function update(Request $request, Jabatan $jabatan): RedirectResponse
    {
        $validated = $request->validate([
            'nama_jabatan' => ['required', 'string', 'max:255'],
            'jenis_jabatan' => ['required', Rule::in(['Struktural', 'Fungsional', 'Pelaksana'])],
        ]);

        $jabatan->update($validated);

        return redirect()->route('admin.jabatans.index')->with('success', 'Jabatan berhasil diperbarui!');
    }

    public function destroy(Jabatan $jabatan): RedirectResponse
    {
        if ($jabatan->pegawais()->count() > 0) {
            return redirect()->route('admin.jabatans.index')->with('error', 'Jabatan tidak bisa dihapus karena masih digunakan oleh pegawai!');
        }

        $jabatan->delete();

        return redirect()->route('admin.jabatans.index')->with('success', 'Jabatan berhasil dihapus!');
    }
}
