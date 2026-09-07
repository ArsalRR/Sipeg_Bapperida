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
        return $this->hasMany(HistoryPegawai::class)->orderBy('tanggal_berlaku', 'desc');
    }

    protected static function booted()
    {
        static::updated(function ($pegawai) {
            $trackedFields = [
                'gelar_depan',
                'gelar_belakang',
                'status_kepegawaian',
                'golongan',
                'jabatan_id',
                'status_pernikahan',
            ];

            if ($pegawai->wasChanged($trackedFields)) {
                HistoryPegawai::create([
                    'pegawai_id' => $pegawai->id,
                    'user_id' => auth()->id(),
                    'gelar_depan' => $pegawai->getOriginal('gelar_depan'),
                    'gelar_belakang' => $pegawai->getOriginal('gelar_belakang'),
                    'status_kepegawaian' => $pegawai->getOriginal('status_kepegawaian'),
                    'golongan' => $pegawai->getOriginal('golongan'),
                    'jabatan_id' => $pegawai->getOriginal('jabatan_id'),
                    'status_pernikahan' => $pegawai->getOriginal('status_pernikahan'),
                    // Use the original effective date for the history record
                    'tanggal_berlaku' => $pegawai->getOriginal('tanggal_berlaku') ?? ($pegawai->getOriginal('updated_at') ?? $pegawai->getOriginal('created_at')),
                ]);
            }
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
        'golongan',
        'status_pernikahan',
        'tanggal_berlaku',
        'foto',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'tanggal_berlaku' => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function jabatan(): BelongsTo
    {
        return $this->belongsTo(Jabatan::class);
    }

    public function getNamaLengkapAttribute(): string
    {
        $gelarDepan = $this->gelar_depan ? $this->gelar_depan . ' ' : '';
        $gelarBelakang = $this->gelar_belakang ? ', ' . $this->gelar_belakang : '';
        
        return $gelarDepan . $this->nama . $gelarBelakang;
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
