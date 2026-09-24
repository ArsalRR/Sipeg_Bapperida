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
        'unit_kerja',
        'bidang_id',
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
        return $this->hasMany(Jabatan::class, 'parent_id');
    }

    public function bidang(): BelongsTo
    {
        return $this->belongsTo(Bidang::class);
    }

    public function pegawais(): HasMany
    {
        return $this->hasMany(Pegawai::class);
    }

    public function getBezettingAttribute(): int
    {
        if ($this->relationLoaded('pegawais')) {
            return $this->pegawais->filter(function($p) {
                return $p->status_kerja === 'Aktif' || is_null($p->status_kerja);
            })->count();
        }

        if (isset($this->attributes['pegawais_count'])) {
            return (int) $this->attributes['pegawais_count'];
        }

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
