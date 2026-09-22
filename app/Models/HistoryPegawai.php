<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HistoryPegawai extends Model
{
    protected $fillable = [
        'pegawai_id',
        'user_id',
        'nama',
        'nip',
        'nik',
        'tempat_lahir',
        'tanggal_lahir',
        'jenis_kelamin',
        'agama',
        'alamat',
        'gelar_depan',
        'gelar_belakang',
        'status_kepegawaian',
        'golongan',
        'jabatan_id',
        'bidang_id',
        'status_pernikahan',
        'status_kerja',
        'tanggal_berlaku',
    ];

    protected $casts = [
        'tanggal_berlaku' => 'date',
        'tanggal_lahir'   => 'date',
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

    public function bidang()
    {
        return $this->belongsTo(Bidang::class);
    }

    public function getGolonganPangkatAttribute(): string
    {
        $gol = $this->golongan;
        if (!$gol || $gol === '-') {
            return '-';
        }

        if (strpos($gol, '(') !== false || preg_match('/(Pembina|Penata|Pengatur|Juru)/i', $gol)) {
            return $gol;
        }

        $clean = strtoupper(trim(str_replace(' ', '/', $gol)));
        if (strpos($clean, '/') === false) {
            $clean = preg_replace('/^(I{1,3}|IV|V)([A-E])$/', '$1/$2', $clean);
        }

        $map = [
            'I/A' => 'Juru Muda',
            'I/B' => 'Juru Muda Tingkat I',
            'I/C' => 'Juru',
            'I/D' => 'Juru Tingkat I',
            'II/A' => 'Pengatur Muda',
            'II/B' => 'Pengatur Muda Tingkat I',
            'II/C' => 'Pengatur',
            'II/D' => 'Pengatur Tingkat I',
            'III/A' => 'Penata Muda',
            'III/B' => 'Penata Muda Tingkat I',
            'III/C' => 'Penata',
            'III/D' => 'Penata Tingkat I',
            'IV/A' => 'Pembina',
            'IV/B' => 'Pembina Tingkat I',
            'IV/C' => 'Pembina Utama Muda',
            'IV/D' => 'Pembina Utama Madya',
            'IV/E' => 'Pembina Utama',
        ];

        if (isset($map[$clean])) {
            $displayGol = str_replace('/', ' ', $clean);
            return $map[$clean] . ' (' . $displayGol . ')';
        }

        return (is_numeric($clean) || in_array($clean, ['I','II','III','IV','V','VI','VII','VIII','IX','X','XI','XII','XIII','XIV','XV','XVI','XVII']))
            ? 'Golongan ' . $gol
            : $gol;
    }
}
