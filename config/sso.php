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
            'desc' => 'Surat Naskah: nomor surat otomatis, arsip surat, dan monitoring nomor kosong.',
            'url'  => env('SURYA_URL', 'http://192.168.254.102:84'),
            'icon' => 'icons/mail.webp',
        ],
        [
            'name' => 'JAGA',
            'desc' => 'Sistem Jaga Asset.',
            'url'  => env('JAGA_URL', 'http://localhost:5173'),
            'icon' => 'icons/assets.webp',
        ],
    ],
];