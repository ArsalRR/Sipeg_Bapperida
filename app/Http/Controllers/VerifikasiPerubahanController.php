<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\PengajuanPerubahan;
use App\Models\Pegawai;
use App\Models\User;
use App\Models\Jabatan;
use App\Models\Bidang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class VerifikasiPerubahanController extends Controller
{
    public function index(Request $request): View
    {
        $status = $request->get('status', 'pending');

        $pengajuans = PengajuanPerubahan::with(['pegawai.jabatan', 'pegawai.bidang', 'user', 'adminApprover'])
            ->when($status !== 'semua', function ($q) use ($status) {
                $q->where('status', $status);
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $counts = [
            'pending'   => PengajuanPerubahan::where('status', 'pending')->count(),
            'disetujui' => PengajuanPerubahan::where('status', 'disetujui')->count(),
            'ditolak'   => PengajuanPerubahan::where('status', 'ditolak')->count(),
            'total'     => PengajuanPerubahan::count(),
        ];

        $allJabatans = Jabatan::all()->keyBy('id');
        $allBidangs = SchemaHasBidangs() ? Bidang::all()->keyBy('id') : collect();

        return view('admin.verifikasi.index', compact('pengajuans', 'counts', 'status', 'allJabatans', 'allBidangs'));
    }

    public function setujui($id): RedirectResponse
    {
        $pengajuan = PengajuanPerubahan::with(['pegawai', 'user'])->findOrFail($id);

        if ($pengajuan->status !== 'pending') {
            return back()->with('error', 'Pengajuan ini sudah diproses sebelumnya.');
        }

        DB::transaction(function () use ($pengajuan) {
            $dataBaru = $pengajuan->data_baru ?? [];
            $pegawai = $pengajuan->pegawai;
            $user = $pengajuan->user;

            // Update User Credentials
            if ($user) {
                $userData = [];
                if (!empty($dataBaru['username']) && $dataBaru['username'] !== $user->username) {
                    $userData['username'] = $dataBaru['username'];
                }
                if (!empty($dataBaru['email']) && $dataBaru['email'] !== $user->email) {
                    $userData['email'] = $dataBaru['email'];
                }
                if (!empty($userData)) {
                    $user->update($userData);
                }
            }

            // Update Pegawai Data
            if ($pegawai) {
                $pegawaiData = [
                    'nama'               => $dataBaru['nama'] ?? $pegawai->nama,
                    'gelar_depan'        => $dataBaru['gelar_depan'] ?? null,
                    'gelar_belakang'     => $dataBaru['gelar_belakang'] ?? null,
                    'nip'                => $dataBaru['nip'] ?? $pegawai->nip,
                    'nik'                => $dataBaru['nik'] ?? $pegawai->nik,
                    'alamat'             => $dataBaru['alamat'] ?? $pegawai->alamat,
                    'tempat_lahir'       => $dataBaru['tempat_lahir'] ?? $pegawai->tempat_lahir,
                    'tanggal_lahir'      => $dataBaru['tanggal_lahir'] ?? $pegawai->tanggal_lahir,
                    'jenis_kelamin'      => $dataBaru['jenis_kelamin'] ?? $pegawai->jenis_kelamin,
                    'agama'              => $dataBaru['agama'] ?? $pegawai->agama,
                    'status_kepegawaian' => $dataBaru['status_kepegawaian'] ?? $pegawai->status_kepegawaian,
                    'bidang_id'          => $dataBaru['bidang_id'] ?? $pegawai->bidang_id,
                    'jabatan_id'         => $dataBaru['jabatan_id'] ?? $pegawai->jabatan_id,
                    'golongan'           => $dataBaru['golongan'] ?? $pegawai->golongan,
                    'status_pernikahan'  => $dataBaru['status_pernikahan'] ?? $pegawai->status_pernikahan,
                    'tanggal_berlaku'    => $dataBaru['tanggal_berlaku'] ?? now()->toDateString(),
                ];

                // Tangani Foto Profil jika ada di temp_foto
                if (!empty($dataBaru['foto_temp']) && Storage::disk('public')->exists($dataBaru['foto_temp'])) {
                    if ($pegawai->foto && Storage::disk('public')->exists($pegawai->foto)) {
                        Storage::disk('public')->delete($pegawai->foto);
                    }

                    $ext = pathinfo($dataBaru['foto_temp'], PATHINFO_EXTENSION);
                    $newPath = 'pegawai/foto/' . uniqid() . '.' . ($ext ?: 'jpg');

                    Storage::disk('public')->move($dataBaru['foto_temp'], $newPath);
                    $pegawaiData['foto'] = $newPath;
                }

                $pegawai->update($pegawaiData);
            }

            $pengajuan->update([
                'status'         => 'disetujui',
                'disetujui_oleh' => auth()->id(),
                'disetujui_pada' => now(),
            ]);
        });

        return back()->with('success', 'Pengajuan perubahan data berhasil disetujui dan diterapkan!');
    }

    public function tolak(Request $request, $id): RedirectResponse
    {
        $pengajuan = PengajuanPerubahan::findOrFail($id);

        if ($pengajuan->status !== 'pending') {
            return back()->with('error', 'Pengajuan ini sudah diproses sebelumnya.');
        }

        $request->validate([
            'catatan_admin' => 'nullable|string|max:500',
        ]);

        // Hapus foto temporary jika ditolak
        $dataBaru = $pengajuan->data_baru ?? [];
        if (!empty($dataBaru['foto_temp']) && Storage::disk('public')->exists($dataBaru['foto_temp'])) {
            Storage::disk('public')->delete($dataBaru['foto_temp']);
        }

        $pengajuan->update([
            'status'         => 'ditolak',
            'catatan_admin'  => $request->catatan_admin ?: 'Pengajuan ditolak oleh Admin.',
            'disetujui_oleh' => auth()->id(),
            'disetujui_pada' => now(),
        ]);

        return back()->with('success', 'Pengajuan perubahan data berhasil ditolak.');
    }
}

function SchemaHasBidangs() {
    return \Illuminate\Support\Facades\Schema::hasTable('bidangs');
}
