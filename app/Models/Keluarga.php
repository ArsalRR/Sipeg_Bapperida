<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Keluarga extends Model
{
    use HasFactory;

    protected $fillable = [
        'pegawai_id',
        'nama',
        'hubungan',
        'pekerjaan',
        'tempat_lahir',
        'tanggal_lahir',
        'tanggal_perkawinan',
        'tunjangan',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'tanggal_perkawinan' => 'date',
    ];

    public function pegawai(): BelongsTo
    {
        return $this->belongsTo(Pegawai::class);
    }
}
