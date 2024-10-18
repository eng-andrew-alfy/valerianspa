<?php

    return [
        'sandbox' => [
            'api_key' => env('TAMARA_API_KEY_SANDBOX'),
            'base_url' => 'https://api-sandbox.tamara.co/',
        ],
        'live' => [
            'api_key' => env('TAMARA_API_KEY'),
            'base_url' => 'https://api.tamara.co/',
        ],
    ];
