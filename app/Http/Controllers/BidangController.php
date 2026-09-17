<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Bidang;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class BidangController extends Controller
{
    private function ensureTableAndDataSeeded(): void
    {
        if (!\Illuminate\Support\Facades\Schema::hasTable('bidangs')) {
            \Illuminate\Support\Facades\Schema::create('bidangs', function (\Illuminate\Database\Schema\Blueprint $table) {
                $table->id();
                $table->string('nama_bidang');
                $table->string('singkatan')->unique();
                $table->timestamps();
            });

            if (\Illuminate\Support\Facades\Schema::hasTable('pegawais') && !\Illuminate\Support\Facades\Schema::hasColumn('pegawais', 'bidang_id')) {
                \Illuminate\Support\Facades\Schema::table('pegawais', function (\Illuminate\Database\Schema\Blueprint $table) {
                    $table->foreignId('bidang_id')->nullable()->after('jabatan_id')->constrained('bidangs')->nullOnDelete();
                });
            }
        }

        if (Bidang::count() === 0) {
            $seeder = new \Database\Seeders\BidangSeeder();
            $seeder->run();
        }
    }

    public function index(): View
    {
        $this->ensureTableAndDataSeeded();
        $bidangs = Bidang::withCount('pegawais')->orderBy('id', 'asc')->get();
        return view('admin.bidang.index', compact('bidangs'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama_bidang' => ['required', 'string', 'max:255'],
            'singkatan'   => ['required', 'string', 'max:50', 'unique:bidangs,singkatan'],
        ], [
            'singkatan.unique' => 'Singkatan bidang sudah digunakan.',
        ]);

        Bidang::create($validated);

        return redirect()->route('admin.bidangs.index')
            ->with('success', 'Data Bidang/Unit Kerja berhasil ditambahkan.');
    }

    public function update(Request $request, Bidang $bidang): RedirectResponse
    {
        $validated = $request->validate([
            'nama_bidang' => ['required', 'string', 'max:255'],
            'singkatan'   => ['required', 'string', 'max:50', Rule::unique('bidangs', 'singkatan')->ignore($bidang->id)],
        ]);

        $bidang->update($validated);

        return redirect()->route('admin.bidangs.index')
            ->with('success', 'Data Bidang/Unit Kerja berhasil diperbarui.');
    }

    public function destroy(Bidang $bidang): RedirectResponse
    {
        if ($bidang->pegawais()->count() > 0) {
            return redirect()->route('admin.bidangs.index')
                ->with('error', 'Bidang/Unit Kerja tidak dapat dihapus karena masih terhubung ke data pegawai.');
        }

        $bidang->delete();

        return redirect()->route('admin.bidangs.index')
            ->with('success', 'Data Bidang/Unit Kerja berhasil dihapus.');
    }
}
