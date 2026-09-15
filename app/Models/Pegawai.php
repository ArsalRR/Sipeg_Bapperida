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

            HistoryPegawai::create([
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
            ]);
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
        'status_kerja',
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
