<?php

declare(strict_types=1);

return [
    'default' => [
        'driver' => env('DB_DRIVER', 'mysql'),
        'host' => env('DB_HOST', '127.0.0.1'),
        'database' => env('DB_DATABASE', 'hex'),
        'port' => env('DB_PORT', 3306),
        'username' => env('DB_USERNAME', 'hex'),
        'password' => env('DB_PASSWORD', 'xFG36cR28ZXYjraF'),
        'charset' => env('DB_CHARSET', 'utf8'),
        'collation' => env('DB_COLLATION', 'utf8_unicode_ci'),
        'prefix' => env('DB_PREFIX', 'hex_'),
        'pool' => [
            'min_connections' => swoole_cpu_num() * 5,
            'max_connections' => swoole_cpu_num() * 20,
            'connect_timeout' => 5.0,
            'wait_timeout' => 3.0,
            'heartbeat' => -1,
            'max_idle_time' => (float)env('DB_MAX_IDLE_TIME', 60),
        ],
        'commands' => [
            'gen:model' => [
                'path' => 'app/Model',
                'force_casts' => true,
                'inheritance' => 'Model',
                'prefix' => env('DB_PREFIX', 'hex_'),
                'refresh-fillable' => true,
                'with-comments' => true
            ],
        ],
    ],
];
