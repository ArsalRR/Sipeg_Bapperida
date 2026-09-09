<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DokumenFile extends Model
{
    use HasFactory;

    protected $fillable = [
        'pegawai_id',
        'jenis_dokumen',
        'keterangan',
        'tahun',
        'file_path',
        'link_file',
    ];

    public function pegawai(): BelongsTo
    {
        return $this->belongsTo(Pegawai::class);
    }
}
