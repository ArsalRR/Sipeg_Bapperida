<?php

return [
    'allowed_redirects' => array_values(array_filter(
        array_map(
            'trim',
            explode(',', env('SSO_ALLOWED_REDIRECTS', ''))
        )
    )),

    'code_ttl'  => 60,
    'token_ttl' => 60 * 60 * 8,

    'apps' => [
        [
            'name' => 'SURYA',
            'desc' => 'Sistem Pengelolaan Surat dan Arsip untuk penomoran surat otomatis, pengarsipan naskah dinas, dan monitoring nomor surat.',
            'url'  => env('SURYA_URL', 'http://localhost:5173'),
            'icon' => 'icons/mail.webp',
        ],
        [
            'name' => 'JAGA',
            'desc' => 'Sistem Pengendalian Aset untuk pencatatan aset, pemantauan pemeliharaan, pengelolaan peminjaman, dan pelaporan biaya perawatan.',
            'url'  => env('JAGA_URL', 'http://localhost:5174'),
            'icon' => 'icons/assets.webp',
        ],
        [
            'name' => 'SAKU',
            'desc' => 'Sistem Pengelolaan Dokumen untuk mengatur folder, menyimpan dan mencari dokumen, serta mengelola akses dokumen secara terstruktur.',
            'url'  => env('SAKU_URL', 'http://192.168.254.191:90'),
            'icon' => 'icons/folder.webp',
        ],
    ],
];