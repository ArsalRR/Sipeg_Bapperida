<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class JenisDokumen extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_jenis',
        'deskripsi',
        'icon',
        'warna',
    ];
}
