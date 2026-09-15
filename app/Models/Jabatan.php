<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Jabatan extends Model
{
    use HasFactory;

    protected $fillable = [
        'parent_id',
        'nama_jabatan',
        'jenis_jabatan',
        'kelas_jabatan',
        'kebutuhan',
        'kategori_warna',
        'jumlah',
    ];

    protected $appends = [
        'bezetting',
        'selisih',
    ];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Jabatan::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Jabatan::class, 'parent_id')->with(['children', 'pegawais']);
    }

    public function pegawais(): HasMany
    {
        return $this->hasMany(Pegawai::class);
    }

    public function getBezettingAttribute(): int
    {
        return $this->pegawais()->where(function($q) {
            $q->where('status_kerja', 'Aktif')->orWhereNull('status_kerja');
        })->count();
    }

    public function getSelisihAttribute(): int
    {
        $kebutuhan = $this->kebutuhan ?? $this->jumlah ?? 0;
        return $this->bezetting - $kebutuhan;
    }
}
