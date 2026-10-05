<?php

return [
    'allowed_redirects' => array_filter(explode(',', env('SSO_ALLOWED_REDIRECTS', ''))),
    'code_ttl'  => 60,
    'token_ttl' => 60 * 60 * 8,

    'apps' => [
        [
            'name' => 'SURYA',
            'desc' => 'Surat Naskah: nomor surat otomatis, arsip surat, dan monitoring nomor kosong.',
            'url'  => env('SURYA_URL', 'http://bapperida.my.id:84'),
        ],
    ],
];