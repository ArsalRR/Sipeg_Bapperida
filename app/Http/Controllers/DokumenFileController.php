<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DokumenFile;
use App\Models\JenisDokumen;
use App\Models\Pegawai;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DokumenFileController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        
        // Ambil daftar master jenis dokumen dinamis dari DB
        $jenisDokumens = JenisDokumen::orderBy('id', 'asc')->get();

        // Hitung jumlah dokumen per jenis (untuk pegawai aktif / scope user)
        $dokumenCounts = DokumenFile::selectRaw('jenis_dokumen, count(*) as total')
            ->when(!in_array($user->role, ['admin', 'superadmin']), function ($q) use ($user) {
                $q->where('pegawai_id', optional($user->pegawai)->id);
            })
            ->groupBy('jenis_dokumen')
            ->pluck('total', 'jenis_dokumen');

        $pegawais = collect();
        if (in_array($user->role, ['admin', 'superadmin'])) {
            $pegawais = Pegawai::orderBy('nama', 'asc')->get();
        }

        return view('dokumen.index', compact('jenisDokumens', 'dokumenCounts', 'pegawais'));
    }

    public function showJenis(Request $request, $id)
    {
        $user = Auth::user();
        $jenisDokumen = JenisDokumen::findOrFail($id);
        $pegawais = collect();

        if (in_array($user->role, ['admin', 'superadmin'])) {
            $pegawais = Pegawai::orderBy('nama', 'asc')->get();
            $query = DokumenFile::with('pegawai')->where('jenis_dokumen', $jenisDokumen->nama_jenis);
            if ($request->filled('pegawai_id')) {
                $query->where('pegawai_id', $request->pegawai_id);
            }
        } else {
            $pegawai = $user->pegawai;
            $query = $pegawai ? $pegawai->dokumenFiles()->where('jenis_dokumen', $jenisDokumen->nama_jenis) : DokumenFile::whereRaw('1 = 0');
        }

        // Filter Tahun
        if ($request->filled('tahun') && $request->tahun != 'semua') {
            $query->where('tahun', $request->tahun);
        }

        // Filter Cari
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('keterangan', 'like', '%' . $request->search . '%')
                  ->orWhere('tahun', 'like', '%' . $request->search . '%');
            });
        }

        $dokumens = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('dokumen.show_jenis', compact('jenisDokumen', 'dokumens', 'pegawais'));
    }

    public function storeJenis(Request $request)
    {
        $user = Auth::user();
        if (!in_array($user->role, ['admin', 'superadmin'])) {
            abort(403, 'Hanya Admin yang dapat menambah jenis dokumen.');
        }

        $request->validate([
            'nama_jenis' => 'required|string|max:255|unique:jenis_dokumens,nama_jenis',
            'deskripsi'  => 'nullable|string|max:255',
        ]);

        JenisDokumen::create([
            'nama_jenis' => $request->nama_jenis,
            'deskripsi'  => $request->deskripsi ?? 'Dokumen Kepegawaian',
            'icon'       => 'folder',
            'warna'      => 'blue',
        ]);

        return back()->with('success', 'Jenis dokumen baru berhasil ditambahkan.');
    }

    public function updateJenis(Request $request, $id)
    {
        $user = Auth::user();
        if (!in_array($user->role, ['admin', 'superadmin'])) {
            abort(403, 'Hanya Admin yang dapat mengedit jenis dokumen.');
        }

        $jenis = JenisDokumen::findOrFail($id);

        $request->validate([
            'nama_jenis' => 'required|string|max:255|unique:jenis_dokumens,nama_jenis,' . $id,
            'deskripsi'  => 'nullable|string|max:255',
        ]);

        $namaLama = $jenis->nama_jenis;
        $jenis->update([
            'nama_jenis' => $request->nama_jenis,
            'deskripsi'  => $request->deskripsi,
        ]);

        if ($namaLama !== $request->nama_jenis) {
            DokumenFile::where('jenis_dokumen', $namaLama)->update(['jenis_dokumen' => $request->nama_jenis]);
        }

        return back()->with('success', 'Jenis dokumen berhasil diperbarui.');
    }

    public function destroyJenis($id)
    {
        $user = Auth::user();
        if (!in_array($user->role, ['admin', 'superadmin'])) {
            abort(403, 'Hanya Admin yang dapat menghapus jenis dokumen.');
        }

        $jenis = JenisDokumen::findOrFail($id);
        $jenis->delete();

        return redirect()->route('dokumen.file.index')->with('success', 'Jenis dokumen berhasil dihapus.');
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $pegawaiId = null;

        if (in_array($user->role, ['admin', 'superadmin'])) {
            $request->validate([
                'pegawai_id' => 'required|exists:pegawais,id',
            ]);
            $pegawaiId = $request->pegawai_id;
        } else {
            if (!$user->pegawai) {
                return back()->with('error', 'Data pegawai tidak ditemukan untuk akun Anda.');
            }
            $pegawaiId = $user->pegawai->id;
        }

        // Validasi input: File Wajib Upload PDF & Max 5MB (5120 KB)
        $request->validate([
            'jenis_dokumen' => 'required|string|max:255',
            'keterangan'    => 'nullable|string|max:500',
            'tahun'         => 'required|numeric|min:1900|max:2100',
            'file_upload'   => 'required|file|mimes:pdf|max:5120', // Hanya PDF, Max 5MB
        ], [
            'file_upload.required' => 'File dokumen wajib diunggah.',
            'file_upload.max'      => 'Ukuran file dokumen maksimal 5 MB.',
            'file_upload.mimes'    => 'Format file harus berupa PDF.',
        ]);

        $filePath = null;
        if ($request->hasFile('file_upload')) {
            $file = $request->file('file_upload');
            $filePath = $file->store('dokumen_files', 'public');
        }

        DokumenFile::create([
            'pegawai_id'    => $pegawaiId,
            'jenis_dokumen' => $request->jenis_dokumen,
            'keterangan'    => $request->keterangan,
            'tahun'         => $request->tahun,
            'file_path'     => $filePath,
        ]);

        return back()->with('success', 'Dokumen berhasil diunggah.');
    }

    public function destroy($id)
    {
        $dokumen = DokumenFile::findOrFail($id);
        $user = Auth::user();

        if (!in_array($user->role, ['admin', 'superadmin'])) {
            if (!$user->pegawai || $dokumen->pegawai_id !== $user->pegawai->id) {
                abort(403, 'Tidak memiliki akses.');
            }
        }

        if ($dokumen->file_path && Storage::disk('public')->exists($dokumen->file_path)) {
            Storage::disk('public')->delete($dokumen->file_path);
        }

        $dokumen->delete();

        return back()->with('success', 'Dokumen berhasil dihapus.');
    }

    public function download($id)
    {
        $dokumen = DokumenFile::findOrFail($id);
        $user = Auth::user();

        if (!in_array($user->role, ['admin', 'superadmin'])) {
            if (!$user->pegawai || $dokumen->pegawai_id !== $user->pegawai->id) {
                abort(403, 'Anda tidak memiliki akses ke dokumen ini.');
            }
        }

        if (!$dokumen->file_path || !Storage::disk('public')->exists($dokumen->file_path)) {
            abort(404, 'File dokumen tidak ditemukan di server.');
        }

        return Storage::disk('public')->response($dokumen->file_path);
    }
}
