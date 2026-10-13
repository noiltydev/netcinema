<?php

declare(strict_types=1);

return [
    'services' => [
        'kodik' => [
            'key' => env('KODIK_API_KEY'),
            'endpoint' => env('KODIK_API_ENDPOINT', 'https://kodik-api.com'),
        ],

        'myanimelist' => [
            'client_id' => env('MYANIMELIST_CLIENT_ID'),
            'client_secret' => env('MYANIMELIST_CLIENT_SECRET'),
        ],

        'shikimori' => [
            'client_id' => env('SHIKIMORI_CLIENT_ID'),
            'client_secret' => env('SHIKIMORI_CLIENT_SECRET'),
        ],
    ],
];
