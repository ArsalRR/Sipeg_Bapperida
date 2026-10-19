<?php

return [
    'disable' => env('CAPTCHA_DISABLE', false),
    'characters' => ['A', 'B', 'C', 'D', 'E', 'F', 'H', 'K', 'M', 'N', 'R', 'T', 'U', 'V', 'W', 'X', 'Z',
        2, 3, 4, 5, 6, 7, 8, 9],

    'default' => [
        'length' => 6,
        'width' => 345,
        'height' => 65,
        'quality' => 90,
        'math' => false,
        'expire' => 60,
        'encrypt' => false,
        'sensitive' => true,
    ],

    'flat' => [
        'length' => 5,
        'fontColors' => ['#2c3e50', '#c0392b', '#16a085', '#8e44ad', '#303f9f', '#f57c00', '#795548'],
        'width' => 260,
        'height' => 80,
        'math' => false,
        'quality' => 100,
        'lines' => 3,
        'bgImage' => false,
        'bgColor' => '#f1f5f9',
        'contrast' => 0,
        'sensitive' => true,
    ],

    'mini' => [
        'length' => 3,
        'width' => 60,
        'height' => 32,
        'sensitive' => true,
    ],

    'inverse' => [
        'length' => 5,
        'width' => 120,
        'height' => 36,
        'quality' => 90,
        'sensitive' => true,
        'angle' => 12,
        'sharpen' => 10,
        'blur' => 2,
        'invert' => false,
        'contrast' => -5,
    ],

    'math' => [
        'length' => 9,
        'width' => 120,
        'height' => 36,
        'quality' => 90,
    ],
];