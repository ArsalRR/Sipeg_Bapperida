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
        $jabatans = Jabatan::with(['parent', 'pegawais'])
            ->withCount(['pegawais' => function ($query) {
                $query->where(function($q) {
                    $q->where('status_kerja', 'Aktif')->orWhereNull('status_kerja');
                });
            }])
            ->orderBy('kelas_jabatan', 'desc')
            ->orderBy('nama_jabatan', 'asc')
            ->get();
        $bidangs = \Illuminate\Support\Facades\Schema::hasTable('bidangs') 
            ? \App\Models\Bidang::orderBy('id', 'asc')->get() 
            : collect();
        return view('admin.jabatan.index', compact('jabatans', 'bidangs'));
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

        // Helper map to normalize short codes to search terms
        $unitCodeMap = [
            'umum' => ['umum', 'subbag umum', 'kepegawaian', 'umpeg'],
            'perencanaan_evaluasi' => ['perencanaan evaluasi', 'subbag perencanaan', 'keuangan', 'renvalkeu'],
            'ppm' => ['pemerintahan', 'pembangunan manusia', 'ppm'],
            'ekonomi' => ['perekonomian', 'ekonomi', 'sda', 'infrastruktur', 'psdaiw'],
            'ppepd' => ['pengendalian', 'evaluasi', 'ppepd', 'perencanaan pengendalian'],
            'litbang' => ['riset', 'inovasi', 'litbang', 'penelitian', 'rida'],
            'sekretariat' => ['sekretariat', 'kepala badan', 'sekretaris'],
        ];

        $normalize = function (string $str): string {
            $s = strtolower(trim($str));
            $s = str_replace('&', 'dan', $s);
            $s = preg_replace('/[^a-z0-9\s_]/', ' ', $s);
            return preg_replace('/\s+/', ' ', $s);
        };

        $resolveCode = function (?string $rawValue) use ($unitCodeMap, $normalize) {
            if (empty($rawValue)) return null;
            $valNorm = $normalize($rawValue);
            foreach ($unitCodeMap as $code => $keywords) {
                if ($valNorm === $normalize($code)) return $code;
                foreach ($keywords as $kw) {
                    if ($valNorm === $normalize($kw)) return $code;
                }
            }
            foreach ($unitCodeMap as $code => $keywords) {
                foreach ($keywords as $kw) {
                    if (str_contains($valNorm, $normalize($kw))) return $code;
                }
            }
            return null;
        };

        // Dynamic lookup helper function for view (with unit_kerja sensitivity)
        $getJData = function(string $nama, int $defaultB, int $defaultK, ?int $defaultKelas = null, ?string $unitKerja = null) use ($allJabatans, $unitCodeMap, $normalize, $resolveCode) {
            $targetCode = $resolveCode($unitKerja) ?? $resolveCode($nama);
            $searchNameNorm = $normalize($nama);

            // 1. Try finding structural head for the target unit code first
            $found = null;
            if ($targetCode !== null) {
                $found = $allJabatans->first(function($item) use ($targetCode, $searchNameNorm, $resolveCode, $normalize) {
                    $itemCode = $resolveCode($item->unit_kerja);
                    if ($itemCode !== $targetCode) return false;

                    $dbNameNorm = $normalize($item->nama_jabatan);

                    if (str_contains($searchNameNorm, 'kepala badan') && !str_contains($dbNameNorm, 'kepala badan')) {
                        return false;
                    }
                    if (str_contains($searchNameNorm, 'sekretaris') && !str_contains($dbNameNorm, 'sekretaris')) {
                        return false;
                    }

                    return ($item->jenis_jabatan === 'Struktural' || str_starts_with($dbNameNorm, 'kepala ') || str_starts_with($dbNameNorm, 'sekretaris '));
                });
            }

            // 2. Fallback: match by name
            if (!$found) {
                $matches = $allJabatans->filter(function($item) use ($searchNameNorm, $normalize) {
                    $dbNameNorm = $normalize($item->nama_jabatan);
                    return $dbNameNorm === $searchNameNorm || str_contains($dbNameNorm, $searchNameNorm) || str_contains($searchNameNorm, $dbNameNorm);
                });

                if ($matches->count() > 0) {
                    // Prioritaskan jabatan yang memiliki pegawai (bezetting terbesar) jika ada duplikat
                    $found = $matches->sortByDesc(function($j) {
                        return $j->bezetting;
                    })->first();
                }
            }

            if ($found) {
                $k = $found->kebutuhan ?? $found->jumlah ?? 0;
                $b = $found->bezetting;
                return [
                    'id' => $found->id,
                    'nama' => $found->nama_jabatan,
                    'B' => $b,
                    'K' => $k,
                    'selisih' => $b - $k,
                    'kelas' => $found->kelas_jabatan ?? $defaultKelas
                ];
            }

            return [
                'id' => null,
                'nama' => $nama,
                'B' => $defaultB,
                'K' => $defaultK,
                'selisih' => $defaultB - $defaultK,
                'kelas' => $defaultKelas
            ];
        };

        // 2. Fetch standalone Fungsionals — semua Jabatan Fungsional di lingkungan Sekretariat
        $sekretarisJabatan = $allJabatans->first(function($j) use ($normalize) {
            $name = $normalize($j->nama_jabatan);
            return str_starts_with($name, 'sekretaris ') && $j->jenis_jabatan === 'Struktural';
        });

        $allFungsionalSekretaris = $allJabatans
            ->filter(function($j) use ($sekretarisJabatan, $resolveCode) {
                if ($j->jenis_jabatan !== 'Fungsional') return false;
                
                if ($sekretarisJabatan && $j->parent_id == $sekretarisJabatan->id) return true;

                $code = $resolveCode($j->unit_kerja);
                return in_array($code, ['sekretariat', 'umum_kepegawaian', 'perencanaan_evaluasi']);
            })
            ->sortBy('nama_jabatan')
            ->values();

        $fungsionalPerencanaan = $allFungsionalSekretaris->filter(function($j) use ($resolveCode) {
            return $resolveCode($j->unit_kerja) === 'perencanaan_evaluasi';
        })->values();

        $fungsionalSekretariat = $allFungsionalSekretaris->reject(function($j) use ($resolveCode) {
            return $resolveCode($j->unit_kerja) === 'perencanaan_evaluasi';
        })->values();

        if ($fungsionalPerencanaan->isEmpty() && $allFungsionalSekretaris->count() > 1) {
            $halfCount = (int) ceil($allFungsionalSekretaris->count() / 2);
            $fungsionalSekretariat = $allFungsionalSekretaris->take($halfCount);
            $fungsionalPerencanaan = $allFungsionalSekretaris->slice($halfCount)->values();
        }

        $standaloneIds = $allFungsionalSekretaris->pluck('id')->all();

        $getChildrenData = function (string $unitOrParentKey) use ($allJabatans, $unitCodeMap, $resolveCode, $normalize, $standaloneIds) {
            $uKeyNorm = $normalize($unitOrParentKey);
            $resolvedCode = $resolveCode($unitOrParentKey);

            // Fallback: find a parent jabatan by name match (used only if unit_kerja code can't resolve)
            $parent = $allJabatans->first(function ($item) use ($uKeyNorm, $normalize) {
                $dbNameNorm = $normalize($item->nama_jabatan);
                return str_contains($dbNameNorm, $uKeyNorm) || str_contains($uKeyNorm, $dbNameNorm);
            });

            // Get children: prefer canonical unit_kerja code match, fallback to parent_id
            $dbChildren = $allJabatans->filter(function ($j) use ($parent, $resolvedCode, $resolveCode) {
                $childCode = $resolveCode($j->unit_kerja);

                if ($resolvedCode !== null && $childCode !== null) {
                    return $childCode === $resolvedCode;
                }

                if ($parent) {
                    return $j->parent_id == $parent->id;
                }

                return false;
            });

            // Exclude structural heads (already rendered as colored header boxes) and standalone boxes
            $dbChildren = $dbChildren->filter(function ($c) use ($parent, $resolvedCode, $standaloneIds) {
                $cNameLower = strtolower(trim($c->nama_jabatan));

                if ($parent && $c->id === $parent->id) {
                    return false;
                }
                if ($c->jenis_jabatan === 'Struktural') {
                    return false;
                }
                if (str_starts_with($cNameLower, 'kepala ') || str_starts_with($cNameLower, 'sekretaris ')) {
                    return false;
                }
                if (in_array($c->id, $standaloneIds)) {
                    return false;
                }

                return true;
            });

            $results = [];
            foreach ($dbChildren as $child) {
                $b = $child->bezetting ?? 0;
                $k = $child->kebutuhan ?? $child->jumlah ?? 0;

                $results[] = [
                    'nama'    => $child->nama_jabatan,
                    'kelas'   => $child->kelas_jabatan,
                    'B'       => $b,
                    'K'       => $k,
                    'selisih' => $b - $k,
                ];
            }

            usort($results, fn ($a, $b) => strcmp($a['nama'], $b['nama']));

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

        return view('admin.jabatan.peta', compact('allJabatans', 'treeJabatans', 'totalAsn', 'totalPns', 'totalPppk', 'rekapJenis', 'getJData', 'getChildrenData', 'fungsionalSekretariat', 'fungsionalPerencanaan'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama_jabatan' => ['required', 'string', 'max:255'],
            'jenis_jabatan' => ['required', Rule::in(['Struktural', 'Fungsional', 'Pelaksana'])],
            'parent_id' => ['nullable', 'exists:jabatans,id'],
            'unit_kerja' => ['nullable', 'string', 'max:255'],
            'bidang_id' => ['nullable', 'exists:bidangs,id'],
            'kelas_jabatan' => ['nullable', 'integer', 'min:1', 'max:15'],
            'kebutuhan' => ['nullable', 'integer', 'min:0'],
            'kategori_warna' => ['nullable', 'string', 'max:50'],
            'jumlah' => ['nullable', 'integer', 'min:0'],
        ]);

        if (empty($validated['kebutuhan']) && isset($validated['jumlah'])) {
            $validated['kebutuhan'] = $validated['jumlah'];
        }

        $this->autoSyncParentAndUnit($validated);

        Jabatan::create($validated);

        return redirect()->route('admin.jabatans.index')->with('success', 'Jabatan berhasil ditambahkan!');
    }

    public function update(Request $request, Jabatan $jabatan): RedirectResponse
    {
        $validated = $request->validate([
            'nama_jabatan' => ['required', 'string', 'max:255'],
            'jenis_jabatan' => ['required', Rule::in(['Struktural', 'Fungsional', 'Pelaksana'])],
            'parent_id' => ['nullable', 'exists:jabatans,id'],
            'unit_kerja' => ['nullable', 'string', 'max:255'],
            'bidang_id' => ['nullable', 'exists:bidangs,id'],
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

        $this->autoSyncParentAndUnit($validated);

        $jabatan->update($validated);

        return redirect()->route('admin.jabatans.index')->with('success', 'Jabatan berhasil diperbarui!');
    }

    private function autoSyncParentAndUnit(array &$validated): void
    {
        // 1. Auto-fill parent_id if unit_kerja is provided but parent_id is missing
        if (!empty($validated['unit_kerja']) && empty($validated['parent_id'])) {
            $unitHeadKeywords = [
                'Subbag Umum' => 'Kepala Sub Bagian Umum',
                'Subbag Perencanaan' => 'Kepala Sub Bagian Perencanaan',
                'Pemerintahan' => 'Kepala Bidang Pemerintahan',
                'Perekonomian' => 'Kepala Bidang Perekonomian',
                'Perencanaan, Pengendalian' => 'Kepala Bidang Perencanaan',
                'Riset' => 'Kepala Bidang Riset',
            ];
            foreach ($unitHeadKeywords as $uKey => $headName) {
                if (str_contains($validated['unit_kerja'], $uKey)) {
                    $head = Jabatan::where('nama_jabatan', 'LIKE', '%' . $headName . '%')->first();
                    if ($head) {
                        $validated['parent_id'] = $head->id;
                        break;
                    }
                }
            }
        }

        // 2. Auto-fill unit_kerja if parent_id is provided but unit_kerja is missing
        if (!empty($validated['parent_id']) && empty($validated['unit_kerja'])) {
            $parent = Jabatan::find($validated['parent_id']);
            if ($parent) {
                $pName = $parent->nama_jabatan;
                if (str_contains($pName, 'Sub Bagian Umum')) {
                    $validated['unit_kerja'] = 'Subbag Umum & Kepegawaian';
                } elseif (str_contains($pName, 'Sub Bagian Perencanaan')) {
                    $validated['unit_kerja'] = 'Subbag Perencanaan Evaluasi & Keuangan';
                } elseif (str_contains($pName, 'Kepala Bidang Pemerintahan')) {
                    $validated['unit_kerja'] = 'Bidang Pemerintahan & Pembangunan Manusia';
                } elseif (str_contains($pName, 'Kepala Bidang Perekonomian')) {
                    $validated['unit_kerja'] = 'Bidang Perekonomian, SDA, Infrastruktur & Kewilayahan';
                } elseif (str_contains($pName, 'Kepala Bidang Perencanaan')) {
                    $validated['unit_kerja'] = 'Bidang Perencanaan, Pengendalian & Evaluasi';
                } elseif (str_contains($pName, 'Kepala Bidang Riset')) {
                    $validated['unit_kerja'] = 'Bidang Riset & Inovasi Daerah';
                } elseif (!empty($parent->unit_kerja)) {
                    $validated['unit_kerja'] = $parent->unit_kerja;
                }
            }
        }

        // 3. Auto-fill bidang_id from unit_kerja (match singkatan in bidangs table)
        if (!empty($validated['unit_kerja']) && empty($validated['bidang_id'])) {
            $bidang = \App\Models\Bidang::where('singkatan', $validated['unit_kerja'])->first();
            if ($bidang) {
                $validated['bidang_id'] = $bidang->id;
            }
        }
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
        // 0. Clean up duplicate records if any exist from previous runs
        $all = Jabatan::all();
        $seen = [];
        $duplicateIds = [];
        foreach ($all as $j) {
            $key = strtolower(trim((string)$j->nama_jabatan)) . '|' . strtolower(trim((string)($j->unit_kerja ?? '')));
            if (isset($seen[$key])) {
                $primaryId = $seen[$key];
                \App\Models\Pegawai::where('jabatan_id', $j->id)->update(['jabatan_id' => $primaryId]);
                Jabatan::where('parent_id', $j->id)->update(['parent_id' => $primaryId]);
                $duplicateIds[] = $j->id;
            } else {
                $seen[$key] = $j->id;
            }
        }

        if (!empty($duplicateIds)) {
            Jabatan::whereIn('id', $duplicateIds)->delete();
        }

        if (Jabatan::exists()) {
            return;
        }

        // 1. Update any legacy/short unit_kerja names in database to official short codes
        $unitReplacements = [
            'Subbag Umum & Kepegawaian' => 'umum',
            'Subbagian Umum dan Kepegawaian' => 'umum',
            'Subbag Perencanaan Evaluasi & Keuangan' => 'perencanaan_evaluasi',
            'Subbagian Perencanaan, Evaluasi dan Keuangan' => 'perencanaan_evaluasi',
            'Bidang Pemerintahan & Pembangunan Manusia' => 'ppm',
            'Bidang Pemerintahan dan Pembangunan Manusia' => 'ppm',
            'Bidang Perekonomian, SDA, Infrastruktur & Kewilayahan' => 'ekonomi',
            'Bidang Perekonomian, Sumber Daya Alam, Infrastruktur dan Kewilayahan' => 'ekonomi',
            'Bidang Perencanaan, Pengendalian & Evaluasi' => 'ppepd',
            'Bidang Perencanaan, Pengendalian dan Evaluasi Pembangunan Daerah' => 'ppepd',
            'Bidang Riset & Inovasi Daerah' => 'litbang',
            'Bidang Penelitian dan Pengembangan' => 'litbang',
            'Sekretariat' => 'sekretariat',
            'Kepala Badan' => 'sekretariat',
        ];

        foreach ($unitReplacements as $old => $new) {
            Jabatan::where('unit_kerja', $old)
                ->orWhere('unit_kerja', 'LIKE', $old . ' %')
                ->orWhere('unit_kerja', 'LIKE', $old . ' - %')
                ->update(['unit_kerja' => $new]);
        }

        $defaults = [
            // TOP LEVEL & ESELON
            ['nama_jabatan' => 'Kepala Badan Perencanaan Pembangunan, Riset, dan Inovasi Daerah', 'jenis_jabatan' => 'Struktural', 'kelas_jabatan' => 14, 'kebutuhan' => 1, 'parent' => null, 'unit' => 'sekretariat'],
            ['nama_jabatan' => 'Sekretaris Badan Perencanaan Pembangunan, Riset, dan Inovasi Daerah', 'jenis_jabatan' => 'Struktural', 'kelas_jabatan' => 12, 'kebutuhan' => 1, 'parent' => 'Kepala Badan', 'unit' => 'sekretariat'],
            ['nama_jabatan' => 'JF Perencana Ahli Madya', 'jenis_jabatan' => 'Fungsional', 'kelas_jabatan' => 11, 'kebutuhan' => 2, 'parent' => 'Kepala Badan', 'unit' => 'sekretariat'],

            // SUBBAG 1: UMUM DAN KEPEGAWAIAN
            ['nama_jabatan' => 'Kepala Sub Bagian Umum dan Kepegawaian', 'jenis_jabatan' => 'Struktural', 'kelas_jabatan' => 9, 'kebutuhan' => 1, 'parent' => 'Sekretaris Badan', 'unit' => 'umum'],
            ['nama_jabatan' => 'Penelaah Teknis Kebijakan', 'jenis_jabatan' => 'Pelaksana', 'kelas_jabatan' => 7, 'kebutuhan' => 2, 'parent' => 'Sub Bagian Umum', 'unit' => 'umum'],
            ['nama_jabatan' => 'Pengolah Data dan Informasi', 'jenis_jabatan' => 'Pelaksana', 'kelas_jabatan' => 6, 'kebutuhan' => 1, 'parent' => 'Sub Bagian Umum', 'unit' => 'umum'],
            ['nama_jabatan' => 'Pengadministrasi Perkantoran', 'jenis_jabatan' => 'Pelaksana', 'kelas_jabatan' => 5, 'kebutuhan' => 1, 'parent' => 'Sub Bagian Umum', 'unit' => 'umum'],

            // SUBBAG 2: PERENCANAAN EVALUASI DAN KEUANGAN
            ['nama_jabatan' => 'Kepala Sub Bagian Perencanaan Evaluasi dan Keuangan', 'jenis_jabatan' => 'Struktural', 'kelas_jabatan' => 9, 'kebutuhan' => 1, 'parent' => 'Sekretaris Badan', 'unit' => 'perencanaan_evaluasi'],
            ['nama_jabatan' => 'JF Pranata Komputer Ahli Pertama', 'jenis_jabatan' => 'Fungsional', 'kelas_jabatan' => 8, 'kebutuhan' => 1, 'parent' => 'Sub Bagian Perencanaan', 'unit' => 'perencanaan_evaluasi'],
            ['nama_jabatan' => 'Penelaah Teknis Kebijakan', 'jenis_jabatan' => 'Pelaksana', 'kelas_jabatan' => 7, 'kebutuhan' => 2, 'parent' => 'Sub Bagian Perencanaan', 'unit' => 'perencanaan_evaluasi'],
            ['nama_jabatan' => 'Pengolah Data dan Informasi', 'jenis_jabatan' => 'Pelaksana', 'kelas_jabatan' => 6, 'kebutuhan' => 2, 'parent' => 'Sub Bagian Perencanaan', 'unit' => 'perencanaan_evaluasi'],
            ['nama_jabatan' => 'JF Pranata Komputer Muda', 'jenis_jabatan' => 'Fungsional', 'kelas_jabatan' => 9, 'kebutuhan' => 1, 'parent' => 'Sub Bagian Perencanaan', 'unit' => 'perencanaan_evaluasi'],

            // BIDANG 1: PEMERINTAHAN DAN PEMBANGUNAN MANUSIA
            ['nama_jabatan' => 'Kepala Bidang Pemerintahan dan Pembangunan Manusia', 'jenis_jabatan' => 'Struktural', 'kelas_jabatan' => 11, 'kebutuhan' => 1, 'parent' => 'Kepala Badan', 'unit' => 'ppm'],
            ['nama_jabatan' => 'JF Perencana Ahli Muda', 'jenis_jabatan' => 'Fungsional', 'kelas_jabatan' => 10, 'kebutuhan' => 2, 'parent' => 'Kepala Bidang Pemerintahan', 'unit' => 'ppm'],
            ['nama_jabatan' => 'JF Perencana Ahli Pertama', 'jenis_jabatan' => 'Fungsional', 'kelas_jabatan' => 8, 'kebutuhan' => 1, 'parent' => 'Kepala Bidang Pemerintahan', 'unit' => 'ppm'],
            ['nama_jabatan' => 'JF Pranata Komputer Muda', 'jenis_jabatan' => 'Fungsional', 'kelas_jabatan' => 9, 'kebutuhan' => 1, 'parent' => 'Kepala Bidang Pemerintahan', 'unit' => 'ppm'],
            ['nama_jabatan' => 'JF Pranata Komputer Pertama', 'jenis_jabatan' => 'Fungsional', 'kelas_jabatan' => 8, 'kebutuhan' => 1, 'parent' => 'Kepala Bidang Pemerintahan', 'unit' => 'ppm'],
            ['nama_jabatan' => 'Penelaah Teknis Kebijakan', 'jenis_jabatan' => 'Pelaksana', 'kelas_jabatan' => 7, 'kebutuhan' => 2, 'parent' => 'Kepala Bidang Pemerintahan', 'unit' => 'ppm'],
            ['nama_jabatan' => 'Pengolah Data dan Informasi', 'jenis_jabatan' => 'Pelaksana', 'kelas_jabatan' => 6, 'kebutuhan' => 1, 'parent' => 'Kepala Bidang Pemerintahan', 'unit' => 'ppm'],

            // BIDANG 2: PEREKONOMIAN, SDA, INFRASTRUKTUR DAN KEWILAYAHAN
            ['nama_jabatan' => 'Kepala Bidang Perekonomian, Sumber Daya Alam, Infrastruktur dan Kewilayahan', 'jenis_jabatan' => 'Struktural', 'kelas_jabatan' => 11, 'kebutuhan' => 1, 'parent' => 'Kepala Badan', 'unit' => 'ekonomi'],
            ['nama_jabatan' => 'JF Perencana Ahli Muda', 'jenis_jabatan' => 'Fungsional', 'kelas_jabatan' => 10, 'kebutuhan' => 2, 'parent' => 'Kepala Bidang Perekonomian', 'unit' => 'ekonomi'],
            ['nama_jabatan' => 'JF Perencana Ahli Pertama', 'jenis_jabatan' => 'Fungsional', 'kelas_jabatan' => 8, 'kebutuhan' => 2, 'parent' => 'Kepala Bidang Perekonomian', 'unit' => 'ekonomi'],
            ['nama_jabatan' => 'JF Pranata Komputer Muda', 'jenis_jabatan' => 'Fungsional', 'kelas_jabatan' => 9, 'kebutuhan' => 1, 'parent' => 'Kepala Bidang Perekonomian', 'unit' => 'ekonomi'],
            ['nama_jabatan' => 'JF Pranata Komputer Pertama', 'jenis_jabatan' => 'Fungsional', 'kelas_jabatan' => 8, 'kebutuhan' => 1, 'parent' => 'Kepala Bidang Perekonomian', 'unit' => 'ekonomi'],
            ['nama_jabatan' => 'Penelaah Teknis Kebijakan', 'jenis_jabatan' => 'Pelaksana', 'kelas_jabatan' => 7, 'kebutuhan' => 2, 'parent' => 'Kepala Bidang Perekonomian', 'unit' => 'ekonomi'],
            ['nama_jabatan' => 'Pengolah Data dan Informasi', 'jenis_jabatan' => 'Pelaksana', 'kelas_jabatan' => 6, 'kebutuhan' => 1, 'parent' => 'Kepala Bidang Perekonomian', 'unit' => 'ekonomi'],

            // BIDANG 3: PERENCANAAN, PENGENDALIAN DAN EVALUASI PEMBANGUNAN DAERAH
            ['nama_jabatan' => 'Kepala Bidang Perencanaan, Pengendalian dan Evaluasi Pembangunan Daerah', 'jenis_jabatan' => 'Struktural', 'kelas_jabatan' => 11, 'kebutuhan' => 1, 'parent' => 'Kepala Badan', 'unit' => 'ppepd'],
            ['nama_jabatan' => 'JF Perencana Ahli Muda', 'jenis_jabatan' => 'Fungsional', 'kelas_jabatan' => 10, 'kebutuhan' => 1, 'parent' => 'Kepala Bidang Perencanaan', 'unit' => 'ppepd'],
            ['nama_jabatan' => 'JF Perencana Ahli Pertama', 'jenis_jabatan' => 'Fungsional', 'kelas_jabatan' => 8, 'kebutuhan' => 1, 'parent' => 'Kepala Bidang Perencanaan', 'unit' => 'ppepd'],
            ['nama_jabatan' => 'JF Pranata Komputer Ahli Muda', 'jenis_jabatan' => 'Fungsional', 'kelas_jabatan' => 9, 'kebutuhan' => 1, 'parent' => 'Kepala Bidang Perencanaan', 'unit' => 'ppepd'],
            ['nama_jabatan' => 'JF Pranata Komputer Ahli Pertama', 'jenis_jabatan' => 'Fungsional', 'kelas_jabatan' => 8, 'kebutuhan' => 2, 'parent' => 'Kepala Bidang Perencanaan', 'unit' => 'ppepd'],
            ['nama_jabatan' => 'Penelaah Teknis Kebijakan', 'jenis_jabatan' => 'Pelaksana', 'kelas_jabatan' => 7, 'kebutuhan' => 2, 'parent' => 'Kepala Bidang Perencanaan', 'unit' => 'ppepd'],
            ['nama_jabatan' => 'Pengolah Data dan Informasi', 'jenis_jabatan' => 'Pelaksana', 'kelas_jabatan' => 6, 'kebutuhan' => 1, 'parent' => 'Kepala Bidang Perencanaan', 'unit' => 'ppepd'],

            // BIDANG 4: RISET DAN INOVASI DAERAH
            ['nama_jabatan' => 'Kepala Bidang Riset dan Inovasi Daerah', 'jenis_jabatan' => 'Struktural', 'kelas_jabatan' => 11, 'kebutuhan' => 1, 'parent' => 'Kepala Badan', 'unit' => 'litbang'],
            ['nama_jabatan' => 'JF Peneliti Ahli Muda', 'jenis_jabatan' => 'Fungsional', 'kelas_jabatan' => 9, 'kebutuhan' => 2, 'parent' => 'Kepala Bidang Riset', 'unit' => 'litbang'],
            ['nama_jabatan' => 'JF Peneliti Ahli Pertama', 'jenis_jabatan' => 'Fungsional', 'kelas_jabatan' => 8, 'kebutuhan' => 2, 'parent' => 'Kepala Bidang Riset', 'unit' => 'litbang'],
            ['nama_jabatan' => 'JF Pranata Komputer Ahli Muda', 'jenis_jabatan' => 'Fungsional', 'kelas_jabatan' => 9, 'kebutuhan' => 1, 'parent' => 'Kepala Bidang Riset', 'unit' => 'litbang'],
            ['nama_jabatan' => 'JF Pranata Komputer Ahli Pertama', 'jenis_jabatan' => 'Fungsional', 'kelas_jabatan' => 8, 'kebutuhan' => 1, 'parent' => 'Kepala Bidang Riset', 'unit' => 'litbang'],
            ['nama_jabatan' => 'JF Analis Data Ilmiah Ahli Pertama', 'jenis_jabatan' => 'Fungsional', 'kelas_jabatan' => 8, 'kebutuhan' => 1, 'parent' => 'Kepala Bidang Riset', 'unit' => 'litbang'],
            ['nama_jabatan' => 'JF Penata Penerbitan Ilmiah Ahli Pertama', 'jenis_jabatan' => 'Fungsional', 'kelas_jabatan' => 8, 'kebutuhan' => 1, 'parent' => 'Kepala Bidang Riset', 'unit' => 'litbang'],
            ['nama_jabatan' => 'Pengolah Data dan Informasi', 'jenis_jabatan' => 'Pelaksana', 'kelas_jabatan' => 6, 'kebutuhan' => 1, 'parent' => 'Kepala Bidang Riset', 'unit' => 'litbang'],
        ];

        // 2. Create or ensure all jabatans exist per unique (nama_jabatan + unit_kerja)
        foreach ($defaults as $j) {
            $parentItem = null;
            if (!empty($j['parent'])) {
                $parentItem = Jabatan::where(function($q) use ($j) {
                    $q->where('nama_jabatan', 'LIKE', '%'.$j['parent'].'%');
                })->first();
            }

            Jabatan::firstOrCreate(
                [
                    'nama_jabatan' => $j['nama_jabatan'],
                    'unit_kerja' => $j['unit'],
                ],
                [
                    'jenis_jabatan' => $j['jenis_jabatan'],
                    'kelas_jabatan' => $j['kelas_jabatan'],
                    'kebutuhan' => $j['kebutuhan'],
                    'parent_id' => $parentItem ? $parentItem->id : null,
                ]
            );
        }
    }
}