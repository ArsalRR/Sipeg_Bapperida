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
        $this->ensureDefaultJabatansSeeded();
        $jabatans = Jabatan::with(['parent', 'pegawais'])->withCount('pegawais')->get();
        return view('admin.jabatan.index', compact('jabatans'));
    }

    public function peta(): View
    {
        $this->ensureDefaultJabatansSeeded();
        $allJabatans = Jabatan::with(['pegawais', 'parent', 'children'])->get();
        
        // Group tree jabatans
        $treeJabatans = Jabatan::whereNull('parent_id')
            ->with(['children', 'pegawais'])
            ->get();

        // Rekap ASN
        $totalPns = \App\Models\Pegawai::where('status_kepegawaian', 'PNS')->where(function($q) {
            $q->where('status_kerja', 'Aktif')->orWhereNull('status_kerja');
        })->count();

        $totalPppk = \App\Models\Pegawai::where('status_kepegawaian', 'PPPK')->where(function($q) {
            $q->where('status_kerja', 'Aktif')->orWhereNull('status_kerja');
        })->count();

        $totalAsn = $totalPns + $totalPppk;

        // Dynamic lookup helper function for view
        $getJData = function(string $nama, int $defaultB, int $defaultK, ?int $defaultKelas = null) use ($allJabatans) {
            $found = $allJabatans->first(function($item) use ($nama) {
                $dbName = strtolower(trim($item->nama_jabatan));
                $searchName = strtolower(trim($nama));
                return $dbName === $searchName || str_contains($dbName, $searchName) || str_contains($searchName, $dbName);
            });

            if ($found) {
                $k = $found->kebutuhan ?? $found->jumlah ?? 0;
                $b = $found->bezetting;
                return [
                    'id' => $found->id,
                    'B' => $b,
                    'K' => $k,
                    'selisih' => $b - $k,
                    'kelas' => $found->kelas_jabatan ?? $defaultKelas
                ];
            }

            return [
                'id' => null,
                'B' => $defaultB,
                'K' => $defaultK,
                'selisih' => $defaultB - $defaultK,
                'kelas' => $defaultKelas
            ];
        };

        // Get dynamic child jabatans from database based on parent_id
        $getChildrenData = function(string $parentNameKey, array $defaultRows) use ($allJabatans) {
            // Find parent
            $parent = $allJabatans->first(function($item) use ($parentNameKey) {
                $dbName = strtolower(trim($item->nama_jabatan));
                $searchName = strtolower(trim($parentNameKey));
                return str_contains($dbName, $searchName) || str_contains($searchName, $dbName);
            });

            if (!$parent) {
                return $defaultRows;
            }

            // Get children explicitly linked via parent_id in DB
            $dbChildren = $allJabatans->where('parent_id', $parent->id);

            // Merge default rows with any extra dbChildren linked via parent_id
            $results = $defaultRows;
            $existingNames = array_map(fn($r) => strtolower(trim($r['nama'])), $results);

            foreach ($dbChildren as $child) {
                $cNameLower = strtolower(trim($child->nama_jabatan));
                if (!in_array($cNameLower, $existingNames)) {
                    $k = $child->kebutuhan ?? $child->jumlah ?? 0;
                    $b = $child->bezetting;
                    $results[] = [
                        'nama' => $child->nama_jabatan,
                        'b' => $b,
                        'k' => $k,
                        'kls' => $child->kelas_jabatan ?? 7,
                        'is_db' => true,
                    ];
                    $existingNames[] = $cNameLower;
                }
            }

            return $results;
        };

        $rekapJenis = [
            'JPT PRATAMA' => ['B' => 0, 'K' => 0],
            'ADMINISTRATOR' => ['B' => 0, 'K' => 0],
            'PENGAWAS' => ['B' => 0, 'K' => 0],
            'FUNGSIONAL' => ['B' => 0, 'K' => 0],
            'PELAKSANA' => ['B' => 0, 'K' => 0],
        ];

        foreach ($allJabatans as $j) {
            $k = $j->kebutuhan ?? $j->jumlah ?? 0;
            $b = $j->bezetting;
            
            if ($j->kelas_jabatan >= 14) {
                $rekapJenis['JPT PRATAMA']['B'] += $b;
                $rekapJenis['JPT PRATAMA']['K'] += $k;
            } elseif ($j->kelas_jabatan >= 11 && $j->jenis_jabatan === 'Struktural') {
                $rekapJenis['ADMINISTRATOR']['B'] += $b;
                $rekapJenis['ADMINISTRATOR']['K'] += $k;
            } elseif ($j->kelas_jabatan >= 9 && $j->jenis_jabatan === 'Struktural') {
                $rekapJenis['PENGAWAS']['B'] += $b;
                $rekapJenis['PENGAWAS']['K'] += $k;
            } elseif ($j->jenis_jabatan === 'Fungsional') {
                $rekapJenis['FUNGSIONAL']['B'] += $b;
                $rekapJenis['FUNGSIONAL']['K'] += $k;
            } else {
                $rekapJenis['PELAKSANA']['B'] += $b;
                $rekapJenis['PELAKSANA']['K'] += $k;
            }
        }

        return view('admin.jabatan.peta', compact('allJabatans', 'treeJabatans', 'totalAsn', 'totalPns', 'totalPppk', 'rekapJenis', 'getJData', 'getChildrenData'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama_jabatan' => ['required', 'string', 'max:255'],
            'jenis_jabatan' => ['required', Rule::in(['Struktural', 'Fungsional', 'Pelaksana'])],
            'parent_id' => ['nullable', 'exists:jabatans,id'],
            'kelas_jabatan' => ['nullable', 'integer', 'min:1', 'max:15'],
            'kebutuhan' => ['nullable', 'integer', 'min:0'],
            'kategori_warna' => ['nullable', 'string', 'max:50'],
            'jumlah' => ['nullable', 'integer', 'min:0'],
        ]);

        if (empty($validated['kebutuhan']) && isset($validated['jumlah'])) {
            $validated['kebutuhan'] = $validated['jumlah'];
        }

        Jabatan::create($validated);

        return redirect()->route('admin.jabatans.index')->with('success', 'Jabatan berhasil ditambahkan!');
    }

    public function update(Request $request, Jabatan $jabatan): RedirectResponse
    {
        $validated = $request->validate([
            'nama_jabatan' => ['required', 'string', 'max:255'],
            'jenis_jabatan' => ['required', Rule::in(['Struktural', 'Fungsional', 'Pelaksana'])],
            'parent_id' => ['nullable', 'exists:jabatans,id'],
            'kelas_jabatan' => ['nullable', 'integer', 'min:1', 'max:15'],
            'kebutuhan' => ['nullable', 'integer', 'min:0'],
            'kategori_warna' => ['nullable', 'string', 'max:50'],
            'jumlah' => ['nullable', 'integer', 'min:0'],
        ]);

        // Prevent self referencing parent_id
        if (!empty($validated['parent_id']) && (int)$validated['parent_id'] === $jabatan->id) {
            return redirect()->back()->with('error', 'Jabatan tidak boleh menjadi atasan untuk dirinya sendiri!');
        }

        if (empty($validated['kebutuhan']) && isset($validated['jumlah'])) {
            $validated['kebutuhan'] = $validated['jumlah'];
        }

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

    private function ensureDefaultJabatansSeeded(): void
    {
        $defaults = [
            ['nama_jabatan' => 'Kepala Badan Perencanaan Pembangunan, Riset, dan Inovasi Daerah', 'jenis_jabatan' => 'Struktural', 'kelas_jabatan' => 14, 'kebutuhan' => 1, 'parent' => null],
            ['nama_jabatan' => 'Sekretaris Badan Perencanaan Pembangunan, Riset, dan Inovasi Daerah', 'jenis_jabatan' => 'Struktural', 'kelas_jabatan' => 12, 'kebutuhan' => 1, 'parent' => 'Kepala Badan'],
            ['nama_jabatan' => 'Kepala Bidang Perekonomian, Sumber Daya Alam, Infrastruktur dan Kewilayahan', 'jenis_jabatan' => 'Struktural', 'kelas_jabatan' => 11, 'kebutuhan' => 1, 'parent' => 'Kepala Badan'],
            ['nama_jabatan' => 'Kepala Bidang Pemerintahan dan Pembangunan Manusia', 'jenis_jabatan' => 'Struktural', 'kelas_jabatan' => 11, 'kebutuhan' => 1, 'parent' => 'Kepala Badan'],
            ['nama_jabatan' => 'Kepala Bidang Perencanaan, Pengendalian dan Evaluasi Pembangunan Daerah', 'jenis_jabatan' => 'Struktural', 'kelas_jabatan' => 11, 'kebutuhan' => 1, 'parent' => 'Kepala Badan'],
            ['nama_jabatan' => 'Kepala Bidang Riset dan Inovasi Daerah', 'jenis_jabatan' => 'Struktural', 'kelas_jabatan' => 11, 'kebutuhan' => 1, 'parent' => 'Kepala Badan'],
            ['nama_jabatan' => 'Kepala Sub Bagian Umum dan Kepegawaian', 'jenis_jabatan' => 'Struktural', 'kelas_jabatan' => 9, 'kebutuhan' => 1, 'parent' => 'Sekretaris Badan'],
            ['nama_jabatan' => 'Kepala Sub Bagian Perencanaan Evaluasi dan Keuangan', 'jenis_jabatan' => 'Struktural', 'kelas_jabatan' => 9, 'kebutuhan' => 1, 'parent' => 'Sekretaris Badan'],
            ['nama_jabatan' => 'JF Perencana Ahli Madya', 'jenis_jabatan' => 'Fungsional', 'kelas_jabatan' => 11, 'kebutuhan' => 2, 'parent' => 'Kepala Badan'],
            ['nama_jabatan' => 'JF Perencana Ahli Muda', 'jenis_jabatan' => 'Fungsional', 'kelas_jabatan' => 10, 'kebutuhan' => 2, 'parent' => 'Kepala Bidang Pemerintahan'],
            ['nama_jabatan' => 'JF Perencana Ahli Pertama', 'jenis_jabatan' => 'Fungsional', 'kelas_jabatan' => 8, 'kebutuhan' => 2, 'parent' => 'Kepala Bidang Pemerintahan'],
            ['nama_jabatan' => 'JF Peneliti Ahli Muda', 'jenis_jabatan' => 'Fungsional', 'kelas_jabatan' => 9, 'kebutuhan' => 2, 'parent' => 'Kepala Bidang Riset'],
            ['nama_jabatan' => 'JF Peneliti Ahli Pertama', 'jenis_jabatan' => 'Fungsional', 'kelas_jabatan' => 8, 'kebutuhan' => 2, 'parent' => 'Kepala Bidang Riset'],
            ['nama_jabatan' => 'JF Pranata Komputer Muda', 'jenis_jabatan' => 'Fungsional', 'kelas_jabatan' => 9, 'kebutuhan' => 1, 'parent' => 'Sub Bagian Perencanaan'],
            ['nama_jabatan' => 'JF Pranata Komputer Ahli Muda', 'jenis_jabatan' => 'Fungsional', 'kelas_jabatan' => 9, 'kebutuhan' => 1, 'parent' => 'Sub Bagian Perencanaan'],
            ['nama_jabatan' => 'JF Pranata Komputer Ahli Pertama', 'jenis_jabatan' => 'Fungsional', 'kelas_jabatan' => 8, 'kebutuhan' => 1, 'parent' => 'Sub Bagian Perencanaan'],
            ['nama_jabatan' => 'JF Analis Data Ilmiah Ahli Pertama', 'jenis_jabatan' => 'Fungsional', 'kelas_jabatan' => 8, 'kebutuhan' => 1, 'parent' => 'Kepala Bidang Riset'],
            ['nama_jabatan' => 'JF Penata Penerbitan Ilmiah Ahli Pertama', 'jenis_jabatan' => 'Fungsional', 'kelas_jabatan' => 8, 'kebutuhan' => 1, 'parent' => 'Kepala Bidang Riset'],
            ['nama_jabatan' => 'JF Arsiparis Pelaksana', 'jenis_jabatan' => 'Fungsional', 'kelas_jabatan' => 6, 'kebutuhan' => 1, 'parent' => 'Sub Bagian Umum'],
            ['nama_jabatan' => 'Penelaah Teknis Kebijakan', 'jenis_jabatan' => 'Pelaksana', 'kelas_jabatan' => 7, 'kebutuhan' => 2, 'parent' => 'Sub Bagian Umum'],
            ['nama_jabatan' => 'Pengolah Data dan Informasi', 'jenis_jabatan' => 'Pelaksana', 'kelas_jabatan' => 6, 'kebutuhan' => 1, 'parent' => 'Sub Bagian Umum'],
            ['nama_jabatan' => 'Pengadministrasi Perkantoran', 'jenis_jabatan' => 'Pelaksana', 'kelas_jabatan' => 5, 'kebutuhan' => 1, 'parent' => 'Sub Bagian Umum'],
        ];

        // 1. Create or ensure all jabatans exist first
        foreach ($defaults as $j) {
            Jabatan::firstOrCreate(
                ['nama_jabatan' => $j['nama_jabatan']],
                [
                    'jenis_jabatan' => $j['jenis_jabatan'],
                    'kelas_jabatan' => $j['kelas_jabatan'],
                    'kebutuhan' => $j['kebutuhan'],
                ]
            );
        }

        // 2. Automatically link parent_id for jabatans if not set
        $all = Jabatan::all();
        foreach ($defaults as $j) {
            if (!empty($j['parent'])) {
                $item = $all->firstWhere('nama_jabatan', $j['nama_jabatan']);
                if ($item && empty($item->parent_id)) {
                    $parentItem = $all->first(function($p) use ($j) {
                        return str_contains(strtolower($p->nama_jabatan), strtolower($j['parent']));
                    });
                    if ($parentItem) {
                        $item->update(['parent_id' => $parentItem->id]);
                    }
                }
            }
        }
    }
}
