<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HistoryPegawai extends Model
{
    protected $fillable = [
        'pegawai_id',
        'user_id',
        'gelar_depan',
        'gelar_belakang',
        'status_kepegawaian',
        'golongan',
        'jabatan_id',
        'status_pernikahan',
        'tanggal_berlaku',
    ];

    protected $casts = [
        'tanggal_berlaku' => 'date',
    ];

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function jabatan()
    {
        return $this->belongsTo(Jabatan::class);
    }
}
