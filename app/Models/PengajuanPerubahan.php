<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PengajuanPerubahan extends Model
{
    protected $fillable = [
        'pegawai_id',
        'user_id',
        'data_lama',
        'data_baru',
        'status',
        'catatan_admin',
        'disetujui_oleh',
        'disetujui_pada',
    ];

    protected $casts = [
        'data_lama' => 'array',
        'data_baru' => 'array',
        'disetujui_pada' => 'datetime',
    ];

    public function pegawai(): BelongsTo
    {
        return $this->belongsTo(Pegawai::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function adminApprover(): BelongsTo
    {
        return $this->belongsTo(User::class, 'disetujui_oleh');
    }
}
