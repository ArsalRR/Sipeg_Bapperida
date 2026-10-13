<?php

return [
    'allowed_redirects' => array_filter(explode(',', env('SSO_ALLOWED_REDIRECTS', ''))),
    'code_ttl'  => 60,
    'token_ttl' => 60 * 60 * 8,

    'apps' => [
        [
            'name'         => 'SURYA',
            'desc'         => 'Surat Naskah: nomor surat otomatis, arsip surat, dan monitoring nomor kosong.',
            'url'          => env('SURYA_URL', 'https://surya.ifaserver.my.id'),
            // SURYA tidak pakai SSO callback lokal (production), buka langsung
            'callback_url' => null,
        ],
        [
            'name'         => 'SIGAP',
            'desc'         => 'Sistem Informasi Kepegawaian ASN.',
            // 'url' = link yang dibuka user (frontend React)
            'url'          => env('SIGAP_URL', 'http://localhost:5174'),
            // 'callback_url' = backend Laravel yang punya route /sso/callback
            'callback_url' => env('SIGAP_CALLBACK_URL', 'http://localhost:8001'),
        ],
    ],

];