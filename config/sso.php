<?php

return [
    'allowed_redirects' => array_values(array_filter(
        array_map(
            'trim',
            explode(',', env('SSO_ALLOWED_REDIRECTS', ''))
        )
    )),

    'code_ttl'  => 300,
    'token_ttl' => 60 * 60 * 8,
    'kawal_webhook_url' => env('KAWAL_WEBHOOK_URL', 'http://localhost:8001/api/webhook/pegawai-sync'),
    'apps' => [
        [
            'name'         => 'SURYA',
            'desc'         => 'Surat Naskah: nomor surat otomatis, arsip surat, dan monitoring nomor kosong.',
            'url'          => env('SURYA_URL', 'http://bapperida.my.id:84'),
            'icon'         => 'icons/mail.webp',
            // SURYA tidak pakai SSO callback lokal (production), buka langsung
            'callback_url' => null,
        ],
        [
            'name'         => 'KAWAL',
            'desc'         => 'Platform Pengawalan Alur & Publikasi Konten.',
            // 'url' = link yang dibuka user (frontend React)
            'url'          => env('KAWAL_URL', 'http://localhost:5173'),
            'icon'         => 'icons/pns.webp',
            // 'callback_url' = backend Laravel yang punya route /sso/callback
            'callback_url' => env('KAWAL_CALLBACK_URL', 'http://localhost:8001/sso/callback'),
        ],
        [
            'name'         => 'JAGA',
            'desc'         => 'Sistem Jaga Asset.',
            'url'          => env('JAGA_URL', 'http://localhost:5173'),
            'icon'         => 'icons/assets.webp',
        ],
    ],
];