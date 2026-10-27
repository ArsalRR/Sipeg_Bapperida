<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Bidang;
use App\Models\Jabatan;
use App\Models\Pegawai;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class PegawaiController extends Controller
{
    /** Kolom yang boleh dicetak ke PDF (key sama dengan key kolom di tabel layar). */
    private const PDF_COLUMNS = [
        'nama'         => 'Nama Lengkap',
        'nip'          => 'NIP',
        'mkg'          => 'MKG',
        'jabatan'      => 'Jabatan',
        'bidang'       => 'Bidang / Unit',
        'status'       => 'Status',
        'status_kerja' => 'Keaktifan',
        'golongan'     => 'Golongan / Pangkat',
        'ttl'          => 'Tempat, Tgl Lahir',
        'jk'           => 'L/P',
        'agama'        => 'Agama',
        'nik'          => 'NIK',
        'pernikahan'   => 'Status Nikah',
        'alamat'       => 'Alamat',
    ];

    private const PDF_DEFAULT_COLUMNS = [
        'nama', 'nip', 'mkg', 'jabatan', 'bidang', 'status', 'status_kerja', 'golongan',
    ];

    private const GOLONGAN_MAP = [
        'I/a'   => 'Juru Muda',        'I/b'   => 'Juru Muda Tingkat I',
        'I/c'   => 'Juru',             'I/d'   => 'Juru Tingkat I',
        'II/a'  => 'Pengatur Muda',    'II/b'  => 'Pengatur Muda Tingkat I',
        'II/c'  => 'Pengatur',         'II/d'  => 'Pengatur Tingkat I',
        'III/a' => 'Penata Muda',      'III/b' => 'Penata Muda Tingkat I',
        'III/c' => 'Penata',           'III/d' => 'Penata Tingkat I',
        'IV/a'  => 'Pembina',          'IV/b'  => 'Pembina Tingkat I',
        'IV/c'  => 'Pembina Utama Muda', 'IV/d' => 'Pembina Utama Madya',
        'IV/e'  => 'Pembina Utama',
    ];

    // ------------------------------------------------------------------
    // CRUD
    // ------------------------------------------------------------------

    public function index(): View
    {
        $historyRelations = ['histories.jabatan', 'histories.user'];
        if (Schema::hasColumn('history_pegawais', 'bidang_id')) {
            $historyRelations[] = 'histories.bidang';
        }

        $pegawais = Pegawai::with(array_merge(['jabatan', 'bidang', 'user'], $historyRelations))
            ->latest()
            ->get();

        $jabatans = Jabatan::withCount(['pegawais' => function ($q) {
            $q->where('status_kerja', 'Aktif')->orWhereNull('status_kerja');
        }])->get(['id', 'nama_jabatan', 'jenis_jabatan', 'kelas_jabatan', 'unit_kerja', 'bidang_id', 'parent_id', 'kebutuhan', 'jumlah']);

        $bidangs = Schema::hasTable('bidangs')
            ? Bidang::orderBy('id', 'asc')->get()
            : collect();

        $users = User::whereDoesntHave('pegawai')->get();

        return view('admin.pegawai.index', compact('pegawais', 'jabatans', 'bidangs', 'users'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->normalizeData($request->validate($this->rules()));

        // Cek kapasitas SEBELUM menyimpan foto, supaya tidak ada file yatim jika ditolak.
        if ($data['status_kerja'] === 'Aktif') {
            $penuh = $this->cekKapasitas((int) $data['jabatan_id']);
            if ($penuh) {
                return back()->withInput()->with(
                    'error',
                    "Jabatan {$penuh['nama']} sudah terisi penuh ({$penuh['terisi']}/{$penuh['maks']} formasi)."
                );
            }
        }

        $foto = $this->simpanFoto($request);
        if ($foto !== null) {
            $data['foto'] = $foto;
        }

        Pegawai::create($data);

        return redirect()->route('admin.pegawais.index')->with('success', 'Pegawai berhasil ditambahkan!');
    }

    public function update(Request $request, Pegawai $pegawai): RedirectResponse
    {
        $data = $this->normalizeData($request->validate($this->rules($pegawai)));

        // Cek kapasitas jika ganti jabatan ATAU mengaktifkan pegawai yang tadinya Tidak Aktif
        $isActivating      = $data['status_kerja'] === 'Aktif' && $pegawai->status_kerja === 'Tidak Aktif';
        $isChangingJabatan = (int) $data['jabatan_id'] !== (int) $pegawai->jabatan_id;

        if ($data['status_kerja'] === 'Aktif' && ($isChangingJabatan || $isActivating)) {
            $penuh = $this->cekKapasitas((int) $data['jabatan_id'], $pegawai->id);
            if ($penuh) {
                return back()->withInput()->with(
                    'error',
                    "Jabatan {$penuh['nama']} sudah terisi penuh oleh pegawai aktif lain ({$penuh['terisi']}/{$penuh['maks']} formasi). Pegawai tidak dapat diaktifkan pada jabatan ini."
                );
            }
        }

        $fotoLama = $pegawai->foto;
        $fotoBaru = $this->simpanFoto($request);
        if ($fotoBaru !== null) {
            $data['foto'] = $fotoBaru;
        }

        $pegawai->update($data);

        // Hapus foto lama hanya setelah data berhasil disimpan
        if ($fotoBaru !== null && $fotoLama) {
            Storage::disk('public')->delete($fotoLama);
        }

        return redirect()->route('admin.pegawais.index')->with('success', 'Data pegawai berhasil diperbarui!');
    }

    public function destroy(Pegawai $pegawai): RedirectResponse
    {
        $foto = $pegawai->foto;

        $pegawai->delete();

        if ($foto) {
            Storage::disk('public')->delete($foto);
        }

        return redirect()->route('admin.pegawais.index')->with('success', 'Pegawai berhasil dihapus!');
    }

    // ------------------------------------------------------------------
    // Export PDF
    // ------------------------------------------------------------------

    /**
     * Export PDF (DomPDF). Mengikuti pencarian, urutan, dan kolom yang tampil di layar.
     * Query: search, sort, dir (asc|desc), columns[]
     */
    public function exportPdf(Request $request)
    {
        @ini_set('memory_limit', '512M');
        @set_time_limit(120);

        $search = trim((string) $request->query('search', ''));
        $sort   = (string) $request->query('sort', 'nama');
        $desc   = $request->query('dir') === 'desc';

        // Kolom: whitelist + urutan tetap sesuai PDF_COLUMNS
        $requested = (array) $request->query('columns', []);
        $columns   = array_values(array_intersect(array_keys(self::PDF_COLUMNS), $requested));
        if ($columns === []) {
            $columns = self::PDF_DEFAULT_COLUMNS;
        }
        $headers = [];
        foreach ($columns as $key) {
            $headers[$key] = self::PDF_COLUMNS[$key];
        }

        $items = Pegawai::with(['jabatan', 'bidang'])
            ->when($search !== '', function ($q) use ($search) {
                $like = '%' . $search . '%';
                $q->where(function ($w) use ($like) {
                    $w->where('nama', 'like', $like)
                      ->orWhere('nip', 'like', $like)
                      ->orWhere('nik', 'like', $like)
                      ->orWhereHas('jabatan', fn ($j) => $j->where('nama_jabatan', 'like', $like))
                      ->orWhereHas('bidang', fn ($b) => $b->where('nama_bidang', 'like', $like)
                                                           ->orWhere('singkatan', 'like', $like));
                });
            })
            ->get();

        // Urutan sama dengan header tabel di layar
        $sortKey = match ($sort) {
            'nip'                => fn ($p) => (string) $p->nip,
            'mkg_total_months'   => fn ($p) => $this->hitungMkgBulan($p) ?? -1,
            'jabatan_nama'       => fn ($p) => mb_strtolower($p->jabatan->nama_jabatan ?? ''),
            'bidang_nama'        => fn ($p) => mb_strtolower($p->bidang->nama_bidang ?? ''),
            'status_kepegawaian' => fn ($p) => mb_strtolower((string) $p->status_kepegawaian),
            'status_kerja'       => fn ($p) => mb_strtolower($p->status_kerja ?: 'Aktif'),
            'golongan'           => fn ($p) => mb_strtolower((string) $p->golongan),
            default              => fn ($p) => mb_strtolower((string) $p->nama),
        };
        $items = $items->sortBy($sortKey, SORT_NATURAL, $desc)->values();

        $rows = $items->map(function (Pegawai $p) {
            $bulan = $this->hitungMkgBulan($p);

            return [
                'nama'         => $this->namaLengkap($p),
                'nip'          => (string) $p->nip,
                'mkg'          => $bulan === null ? '-' : intdiv($bulan, 12) . ' Tahun ' . ($bulan % 12) . ' Bulan',
                'jabatan'      => $p->jabatan->nama_jabatan ?? '-',
                'bidang'       => $p->bidang->singkatan ?? ($p->bidang->nama_bidang ?? '-'),
                'status'       => $p->status_kepegawaian ?: '-',
                'status_kerja' => $p->status_kerja ?: 'Aktif',
                'golongan'     => $this->golonganLabel($p->golongan),
                'ttl'          => ($p->tempat_lahir ?: '-') . ', ' . ($p->tanggal_lahir
                                    ? Carbon::parse($p->tanggal_lahir)->locale('id')->translatedFormat('d M Y')
                                    : '-'),
                'jk'           => $p->jenis_kelamin ?: '-',
                'agama'        => $p->agama ?: '-',
                'nik'          => (string) ($p->nik ?: '-'),
                'pernikahan'   => $p->status_pernikahan ?: '-',
                'alamat'       => $p->alamat ?: '-',
            ];
        });

        $pdf = Pdf::loadView('admin.pegawai.pdf', [
            'headers'      => $headers,
            'rows'         => $rows,
            'search'       => $search,
            'tanggalCetak' => now()->locale('id')->translatedFormat('d F Y'),
        ])->setPaper('a4', 'landscape');

        return $pdf->download('Data_Pegawai_BAPERAN_' . now()->format('Y-m-d') . '.pdf');
    }

    // ------------------------------------------------------------------
    // Helper
    // ------------------------------------------------------------------

    /** Aturan validasi bersama untuk store & update. */
    private function rules(?Pegawai $pegawai = null): array
    {
        $unique = fn (string $kolom) => Rule::unique('pegawais', $kolom)->ignore($pegawai?->id);

        return [
            'nama'               => ['required', 'string', 'max:255'],
            'gelar_depan'        => ['nullable', 'string', 'max:50'],
            'gelar_belakang'     => ['nullable', 'string', 'max:50'],
            'nip'                => ['required', 'string', 'size:18', $unique('nip')],
            'nik'                => ['required', 'string', 'size:16', $unique('nik')],
            'alamat'             => ['required', 'string'],
            'tempat_lahir'       => ['required', 'string', 'max:100'],
            'tanggal_lahir'      => ['required', 'date'],
            'jenis_kelamin'      => ['required', Rule::in(['Laki-laki', 'Perempuan'])],
            'agama'              => ['required', 'string', 'max:50'],
            'status_kepegawaian' => ['required', Rule::in(['PNS', 'PPPK', 'CPNS', 'PPPK Paruh Waktu', 'Non ASN'])],
            'jabatan_id'         => ['required', 'exists:jabatans,id'],
            'bidang_id'          => ['required', 'exists:bidangs,id'],
            'golongan'           => ['nullable', 'string', 'max:50'],
            'mkg_tahun'          => ['nullable', 'integer', 'min:0', 'max:60'],
            'mkg_bulan'          => ['nullable', 'integer', 'min:0', 'max:11'],
            'status_pernikahan'  => ['required', Rule::in(['Lajang', 'Menikah', 'Cerai Hidup', 'Cerai Mati'])],
            'status_kerja'       => ['required', Rule::in(['Aktif', 'Tidak Aktif'])],
            'user_id'            => ['nullable', 'exists:users,id', $unique('user_id')],
            'tanggal_berlaku'    => ['nullable', 'date'],
            'foto'               => ['nullable', 'image', 'mimes:jpeg,jpg,png', 'max:2048'],
            'cropped_foto'       => ['nullable', 'string'],
        ];
    }

    /** Rapikan data tervalidasi: tanggal berlaku, MKG, golongan, dan buang field foto mentah. */
    private function normalizeData(array $data): array
    {
        if (empty($data['tanggal_berlaku'])) {
            $data['tanggal_berlaku'] = now()->toDateString();
        }

        // MKG manual: kosong = otomatis (null). Bulan default 0 jika tahun diisi.
        $tahun = $data['mkg_tahun'] ?? null;
        $data['mkg_tahun'] = ($tahun === null || $tahun === '') ? null : (int) $tahun;
        $data['mkg_bulan'] = $data['mkg_tahun'] === null ? null : (int) ($data['mkg_bulan'] ?? 0);

        // Select golongan yang disabled tidak ikut terkirim, jadi Non ASN harus dikosongkan eksplisit.
        $data['golongan'] = $data['status_kepegawaian'] === 'Non ASN'
            ? null
            : (($data['golongan'] ?? null) ?: null);

        // File mentah dari validator tidak boleh masuk ke model
        unset($data['foto'], $data['cropped_foto']);

        return $data;
    }

    /**
     * Simpan foto dari hasil crop (base64) atau upload biasa.
     * Mengembalikan path baru, atau null jika tidak ada foto baru / foto tidak valid.
     */
    private function simpanFoto(Request $request): ?string
    {
        if ($request->filled('cropped_foto')) {
            $parts = explode(';base64,', (string) $request->input('cropped_foto'), 2);

            if (count($parts) === 2 && str_starts_with($parts[0], 'data:image/')) {
                $biner = base64_decode($parts[1], true);

                if ($biner !== false
                    && strlen($biner) <= 2 * 1024 * 1024
                    && @getimagesizefromstring($biner) !== false) {
                    $path = 'pegawai/foto/' . Str::uuid() . '.jpg';
                    Storage::disk('public')->put($path, $biner);

                    return $path;
                }
            }

            return null;
        }

        if ($request->hasFile('foto')) {
            return $request->file('foto')->store('pegawai/foto', 'public');
        }

        return null;
    }

    /**
     * Cek formasi jabatan. Mengembalikan detail jika sudah penuh, null jika masih ada slot.
     *
     * @return array{nama: string, terisi: int, maks: int}|null
     */
    private function cekKapasitas(int $jabatanId, ?int $kecualiPegawaiId = null): ?array
    {
        $jabatan = Jabatan::find($jabatanId);
        if (!$jabatan) {
            return null;
        }

        $maks = $jabatan->kebutuhan ?? $jabatan->jumlah;
        if ($maks === null || (int) $maks <= 0) {
            return null;
        }

        $terisi = $jabatan->pegawais()
            ->when($kecualiPegawaiId, fn ($q) => $q->where('id', '!=', $kecualiPegawaiId))
            ->where(function ($q) {
                $q->where('status_kerja', 'Aktif')->orWhereNull('status_kerja');
            })
            ->count();

        return $terisi >= (int) $maks
            ? ['nama' => (string) $jabatan->nama_jabatan, 'terisi' => $terisi, 'maks' => (int) $maks]
            : null;
    }

    private function namaLengkap(Pegawai $p): string
    {
        return trim(($p->gelar_depan ? $p->gelar_depan . ' ' : '') . $p->nama)
            . ($p->gelar_belakang ? ', ' . $p->gelar_belakang : '');
    }

    /**
     * Total bulan MKG. Prioritas: manual override -> NIP -> tanggal_berlaku.
     * Null jika tidak bisa dihitung.
     */
    private function hitungMkgBulan(Pegawai $p): ?int
    {
        if ($p->mkg_tahun !== null) {
            return ((int) $p->mkg_tahun) * 12 + (int) ($p->mkg_bulan ?? 0);
        }

        $now = now();
        $tmt = null;

        // NIP: posisi 9-12 = tahun TMT, 13-14 = bulan TMT
        $nip = (string) $p->nip;
        if (strlen($nip) >= 14) {
            $y = (int) substr($nip, 8, 4);
            $m = (int) substr($nip, 12, 2);
            if ($y >= 1950 && $y <= $now->year && $m >= 1 && $m <= 12) {
                $tmt = Carbon::create($y, $m, 1);
            }
        }

        if (!$tmt && $p->tanggal_berlaku) {
            $tb  = Carbon::parse($p->tanggal_berlaku);
            $tmt = Carbon::create($tb->year, $tb->month, 1);
        }

        if (!$tmt) {
            return null;
        }

        return max(0, ($now->year - $tmt->year) * 12 + ($now->month - $tmt->month));
    }
    private function golonganLabel(?string $gol): string
    {
        if (!$gol) {
            return '-';
        }

        return isset(self::GOLONGAN_MAP[$gol])
            ? self::GOLONGAN_MAP[$gol] . ' (' . strtoupper(str_replace('/', ' ', $gol)) . ')'
            : 'Golongan ' . $gol;
    }
}