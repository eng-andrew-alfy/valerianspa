<?php // config/flasher.php

return [
    // Default notification library (e.g., 'flasher', 'toastr', 'noty', etc.)
    'default' => 'flasher',

    // Path to the main JavaScript file of PHPFlasher
    'main_script' => '/vendor/flasher/flasher.min.js',

    // Path to the stylesheets for PHPFlasher notifications
    'styles' => [
        '/vendor/flasher/flasher.min.css',
    ],

    // Enable translation of PHPFlasher messages using Laravel's translator service
    'translate' => true,

    // Automatically inject PHPFlasher assets in HTML response
    'inject_assets' => true,

    // Global options
    'options' => [
        'timeout' => 5000, // in milliseconds
        'position' => 'top-right',
    ],

    // Configuration for the flash bag (converting Laravel flash messages)
    // Map Laravel session keys to PHPFlasher types
    'flash_bag' => [
        'success' => ['success'],
        'error' => ['error', 'danger'],
        'warning' => ['warning', 'alarm'],
        'info' => ['info', 'notice', 'alert'],
    ],

    // Filter criteria for notifications (e.g., limit number, types)
    'filter' => [
        'limit' => 5, // Limit the number of displayed notifications
    ],
];
