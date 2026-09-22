<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Pegawai;

class DokumenController extends Controller
{
    public function cetakKp4(Request $request)
    {
        $user = Auth::user();
        $allPegawais = collect();

        if (in_array($user->role, ['admin', 'superadmin'])) {
            $allPegawais = Pegawai::orderBy('nama', 'asc')->get();
            $pegawaiId = $request->get('pegawai_id', optional($allPegawais->first())->id);
            $pegawai = $pegawaiId ? Pegawai::with(['jabatan', 'keluargas', 'histories.jabatan'])->find($pegawaiId) : null;
        } else {
            $pegawai = $user->pegawai ? $user->pegawai->load(['jabatan', 'keluargas', 'histories.jabatan']) : null;
        }

        if ($pegawai && $pegawai->keluargas) {
            $pegawai->setRelation('keluargas', $pegawai->keluargas->sort(function ($a, $b) {
                $order = ['Suami' => 0, 'Istri' => 0, 'Anak' => 1];
                $oA = $order[$a->hubungan] ?? 2;
                $oB = $order[$b->hubungan] ?? 2;
                if ($oA !== $oB) return $oA - $oB;
                return strcmp((string)$a->tanggal_lahir, (string)$b->tanggal_lahir);
            })->values());
        }

        // Tahun berjalan saat ini
        $currentYear = (int) date('Y');

        // Generasi daftar pilihan tahun dari 2026 sampai tahun berjalan (tidak boleh melebihi tahun sekarang)
        $startYear = 2026;
        $maxAvailableYear = max($startYear, $currentYear);
        $availableYears = range($startYear, $maxAvailableYear);

        // Tahun KP4 Pilihan (Default: Tahun berjalan saat ini, dan dipastikan tidak melebihi tahun sekarang)
        $selectedYear = (int) $request->get('tahun', $maxAvailableYear);
        if ($selectedYear > $currentYear) {
            $selectedYear = $currentYear;
        }

        // Tanggal Patokan Resmi Cetak KP4: 03 Januari [Tahun Pilihan]
        $dateCutoff = sprintf('%d-01-03', $selectedYear);
        $tanggalSurat = sprintf('01 Januari %d', $selectedYear);

        $activeHistory = null;

        if ($pegawai) {
            // Ambil riwayat pegawai yang berlaku tepat pada/sebelum 03 Januari tahun tersebut
            $activeHistory = $pegawai->histories()
                ->where('tanggal_berlaku', '<=', $dateCutoff)
                ->orderBy('tanggal_berlaku', 'desc')
                ->first();
        }

        return view('dokumen.kp4', compact('pegawai', 'allPegawais', 'activeHistory', 'tanggalSurat', 'selectedYear', 'availableYears'));
    }
}
