<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pegawai extends Model
{
    use HasFactory, SoftDeletes;

    public function histories()
    {
        return $this->hasMany(HistoryPegawai::class)->latest();
    }

    public function me_dokumenFiles()
    {
        return $this->hasMany(DokumenFile::class);
    }

    public function keluargas()
    {
        return $this->hasMany(Keluarga::class);
    }

    public function dokumenFiles()
    {
        return $this->hasMany(DokumenFile::class);
    }


    protected static function booted()
    {
        static::saved(function ($pegawai) {
            $tanggalBerlaku = $pegawai->tanggal_berlaku 
                ? ($pegawai->tanggal_berlaku instanceof \Carbon\Carbon ? $pegawai->tanggal_berlaku->format('Y-m-d') : (string)$pegawai->tanggal_berlaku)
                : now()->toDateString();

            $historyData = [
                'pegawai_id'         => $pegawai->id,
                'user_id'            => auth()->id() ?? $pegawai->user_id,
                'nama'               => $pegawai->nama,
                'nip'                => $pegawai->nip,
                'nik'                => $pegawai->nik,
                'tempat_lahir'       => $pegawai->tempat_lahir,
                'tanggal_lahir'      => $pegawai->tanggal_lahir,
                'jenis_kelamin'      => $pegawai->jenis_kelamin,
                'agama'              => $pegawai->agama,
                'alamat'             => $pegawai->alamat,
                'gelar_depan'        => $pegawai->gelar_depan,
                'gelar_belakang'     => $pegawai->gelar_belakang,
                'status_kepegawaian' => $pegawai->status_kepegawaian,
                'golongan'           => $pegawai->golongan,
                'jabatan_id'         => $pegawai->jabatan_id,
                'status_pernikahan'  => $pegawai->status_pernikahan,
                'status_kerja'       => $pegawai->status_kerja ?? 'Aktif',
                'tanggal_berlaku'    => $tanggalBerlaku,
            ];

            if (\Illuminate\Support\Facades\Schema::hasColumn('history_pegawais', 'bidang_id')) {
                $historyData['bidang_id'] = $pegawai->bidang_id;
            }

            HistoryPegawai::create($historyData);
        });
    }

    protected $fillable = [
        'user_id',
        'nama',
        'gelar_depan',
        'gelar_belakang',
        'nip',
        'nik',
        'alamat',
        'tempat_lahir',
        'tanggal_lahir',
        'jenis_kelamin',
        'agama',
        'status_kepegawaian',
        'jabatan_id',
        'bidang_id',
        'golongan',
        'status_pernikahan',
        'status_kerja',
        'tanggal_berlaku',
        'foto',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'tanggal_berlaku' => 'date',
    ];

    protected $appends = [
        'nama_lengkap',
        'mkg',
        'kgb_info',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function jabatan(): BelongsTo
    {
        return $this->belongsTo(Jabatan::class);
    }

    public function bidang(): BelongsTo
    {
        return $this->belongsTo(Bidang::class);
    }

    public function getNamaLengkapAttribute(): string
    {
        $gelarDepan = $this->gelar_depan ? $this->gelar_depan . ' ' : '';
        $gelarBelakang = $this->gelar_belakang ? ', ' . $this->gelar_belakang : '';
        
        return $gelarDepan . $this->nama . $gelarBelakang;
    }

    public function calculateMkg($targetDate = null): string
    {
        $now = $targetDate ? \Carbon\Carbon::parse($targetDate) : \Carbon\Carbon::now();

        // 1. Coba ekstrak TMT dari NIP (18 digit atau minimal 14 digit)
        $tmtDate = null;
        if ($this->nip && strlen((string)$this->nip) >= 14) {
            $nipStr = (string)$this->nip;
            $tmtYear = (int)substr($nipStr, 8, 4);
            $tmtMonth = (int)substr($nipStr, 12, 2);

            if ($tmtYear >= 1950 && $tmtYear <= (int)$now->format('Y') && $tmtMonth >= 1 && $tmtMonth <= 12) {
                try {
                    $tmtDate = \Carbon\Carbon::createFromDate($tmtYear, $tmtMonth, 1);
                } catch (\Throwable $e) {}
            }
        }

        // 2. Jika NIP tidak valid / Non-ASN, coba gunakan tanggal_berlaku
        if (!$tmtDate && $this->tanggal_berlaku) {
            try {
                $tmtDate = \Carbon\Carbon::parse($this->tanggal_berlaku)->startOfMonth();
            } catch (\Throwable $e) {}
        }

        if (!$tmtDate) {
            return '-';
        }

        if ($tmtDate->gt($now)) {
            return '0 Tahun 0 Bulan';
        }

        $diff = $tmtDate->diff($now);
        return "{$diff->y} Tahun {$diff->m} Bulan";
    }

    public function getMkgAttribute(): string
    {
        return $this->calculateMkg();
    }

    /**
     * Ambil Tanggal TMT Pengangkatan Awal
     */
    public function getTmtPengangkatanAttribute()
    {
        if ($this->nip && strlen((string)$this->nip) >= 14) {
            $nipStr = (string)$this->nip;
            $tmtYear = (int)substr($nipStr, 8, 4);
            $tmtMonth = (int)substr($nipStr, 12, 2);

            if ($tmtYear >= 1950 && $tmtMonth >= 1 && $tmtMonth <= 12) {
                try {
                    return \Carbon\Carbon::createFromDate($tmtYear, $tmtMonth, 1);
                } catch (\Throwable $e) {}
            }
        }

        if ($this->tanggal_berlaku) {
            try {
                return \Carbon\Carbon::parse($this->tanggal_berlaku)->startOfMonth();
            } catch (\Throwable $e) {}
        }

        return null;
    }

    /**
     * Hitung Tanggal Kenaikan Gaji Berkala (KGB) Berikutnya:
     * - Golongan I, III, IV: Kenaikan pada tahun GENAP (tiap 2 tahun dari TMT pengangkatan: +2, +4, +6, ...)
     * - Golongan II: Kenaikan pada tahun GANJIL (tiap 2 tahun dari TMT pengangkatan: +1, +3, +5, ...)
     */
    public function getKgbNextDateAttribute()
    {
        $tmt = $this->tmt_pengangkatan;
        if (!$tmt) return null;

        $now = \Carbon\Carbon::now()->startOfMonth();

        // Cek apakah Golongan II (II/a, II/b, II/c, II/d, II, atau 2)
        $gol = (string)($this->golongan ?? '');
        $isGolongan2 = (strpos($gol, 'II') === 0 || strpos($gol, '2') === 0);

        $mkgDiffMonths = $tmt->diffInMonths($now);
        $mkgYears = (int)floor($mkgDiffMonths / 12);

        if ($isGolongan2) {
            // Golongan 2: kenaikan pada tahun ganjil (Masa Kerja 1, 3, 5, 7, ...)
            $targetMkgYears = ($mkgYears % 2 === 1) ? $mkgYears : ($mkgYears + 1);
        } else {
            // Golongan 1, 3, 4: kenaikan pada tahun genap (Masa Kerja 2, 4, 6, 8, ...)
            $targetMkgYears = ($mkgYears % 2 === 0 && $mkgYears > 0) ? $mkgYears : (ceil(($mkgYears + 0.1) / 2) * 2);
        }

        // Tanggal KGB berikutnya
        $kgbDate = $tmt->copy()->addYears((int)$targetMkgYears);
        if ($kgbDate->lt($now)) {
            $kgbDate->addYears(2);
        }

        return $kgbDate;
    }

    /**
     * Informasi Status KGB & Peringatan H-2 Bulan
     */
    public function getKgbInfoAttribute(): array
    {
        $nextDate = $this->kgb_next_date;
        if (!$nextDate) {
            return [
                'is_due_soon' => false,
                'due_date' => null,
                'days_left' => null,
                'months_left' => null,
                'message' => '-'
            ];
        }

        $now = \Carbon\Carbon::now();
        $monthsLeft = (int)$now->diffInMonths($nextDate, false);
        $daysLeft = (int)round($now->diffInDays($nextDate, false));

        // Notifikasi aktif jika sisa waktu <= 2 bulan (kira-kira <= 62 hari) dan belum lewat
        $isDueSoon = ($daysLeft >= 0 && $daysLeft <= 62);

        return [
            'is_due_soon' => $isDueSoon,
            'due_date' => $nextDate->format('d/m/Y'),
            'days_left' => $daysLeft,
            'months_left' => $monthsLeft,
            'message' => "KGB Pegawai {$this->nama_lengkap} pada " . $nextDate->format('d/m/Y') . " (" . max(0, $daysLeft) . " hari lagi)."
        ];
    }

    public function getDataAtDate($date)
    {
        $history = $this->histories()
            ->where('tanggal_berlaku', '<=', $date)
            ->orderBy('tanggal_berlaku', 'desc')
            ->first();

        if ($history) {
            return $history;
        }

        return $this;
    }
}
