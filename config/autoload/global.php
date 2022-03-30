<?php

return [
    'doctrine' => [
        'connection' => [
            'driver' => 'pdo_pgsql',
            'host' => 'support',
            'dbname' => 'support',
            'user' => 'support',
            'password' => 'support',
        ],
        'proxyDirectory' => 'data/cache/doctrine-orm-proxies',
        'proxyNamespace' => 'Support',
        'migrations' => [
            'table_storage' => [
                'table_name' => 'application_migration',
            ],
            'all_or_nothing' => true,
            'migrations_paths' => [],
        ],
    ],
    'router' => [
        'fastroute' => [
            'cache_enabled' => true,
            'cache_file' => 'data/cache/fastroute.php.cache',
        ],
    ],
    'support' => [
        'siteTitle' => 'phpMySupport',
        'siteLink' => '/',
        'enableSearch' => true,
        'leftMenuItems' => [],
        'rightMenuItems' => [],
    ],
];
