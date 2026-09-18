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
            // Sort keluargas: Suami/Istri first, then Anak by tanggal_lahir ASC (oldest first)
            $pegawais->each(function ($p) {
                $p->setRelation('keluargas', $p->keluargas->sort(function ($a, $b) {
                    $order = ['Suami' => 0, 'Istri' => 0, 'Anak' => 1];
                    $oA = $order[$a->hubungan] ?? 2;
                    $oB = $order[$b->hubungan] ?? 2;
                    if ($oA !== $oB) return $oA - $oB;
                    // Both same type – sort Anak by tanggal_lahir ASC (oldest first)
                    return strcmp((string)$a->tanggal_lahir, (string)$b->tanggal_lahir);
                })->values());
            });
            $keluargas = Keluarga::with('pegawai')->latest()->get();
        } else {
            // User biasa hanya melihat & mengelola data keluarganya sendiri
            $pegawai = $user->pegawai ? $user->pegawai->load(['keluargas', 'jabatan']) : null;
            $keluargas = $pegawai
                ? $pegawai->keluargas()->with('pegawai')
                    ->orderByRaw("FIELD(hubungan, 'Suami', 'Istri', 'Anak')")
                    ->orderBy('tanggal_lahir', 'asc')
                    ->get()
                : collect();
            $pegawais = $pegawai ? collect([$pegawai]) : collect();
        }

        return view('keluarga.index', compact('keluargas', 'pegawais'));
    }

    public function store(Request $request)
    {
        $this->ensurePekerjaanColumnSafe();
        $user = Auth::user();

        $allowedPekerjaan = [
            'ASN', 'Swasta', 'BUMN', 'BUMD', 'IRT',
            'Pelajar / Mahasiswa', 'Tidak Bekerja', 'Belum/Tidak Bekerja',
            'SD', 'SMP', 'SMA/SMK', 'Mahasiswa',
            'Pensiunan', 'Wiraswasta', 'Lainnya'
        ];

        $rules = [
            'nama' => 'required|string|max:255',
            'hubungan' => 'required|string|max:255',
            'pekerjaan' => ['required', 'string', \Illuminate\Validation\Rule::in($allowedPekerjaan)],
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
        $this->ensurePekerjaanColumnSafe();
        $user = Auth::user();

        // Otorisasi: jika user biasa, pastikan milik pegawainya sendiri
        if (!in_array($user->role, ['admin', 'superadmin'])) {
            if (!$user->pegawai || $keluarga->pegawai_id !== $user->pegawai->id) {
                abort(403, 'Akses tidak diizinkan.');
            }
        }

        $allowedPekerjaan = [
            'ASN', 'Swasta', 'BUMN', 'BUMD', 'IRT',
            'Pelajar / Mahasiswa', 'Tidak Bekerja', 'Belum/Tidak Bekerja',
            'SD', 'SMP', 'SMA/SMK', 'Mahasiswa',
            'Pensiunan', 'Wiraswasta', 'Lainnya'
        ];

        $rules = [
            'nama' => 'required|string|max:255',
            'hubungan' => 'required|string|max:255',
            'pekerjaan' => ['required', 'string', \Illuminate\Validation\Rule::in($allowedPekerjaan)],
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

    private function ensurePekerjaanColumnSafe(): void
    {
        static $checked = false;
        if ($checked) return;
        $checked = true;

        try {
            \Illuminate\Support\Facades\DB::statement("ALTER TABLE keluargas MODIFY COLUMN pekerjaan VARCHAR(255) NULL");
        } catch (\Throwable $e) {
            // Ignore if DB alter fails
        }
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
