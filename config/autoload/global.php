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
    'phpMySupport' => [
        'siteTheme' => 'default',
        'siteThemeAdmin' => 'default',
        'siteTitle' => 'phpMySupport',
        'siteDescription' => null,
        'siteBasePath' => '/',
        'siteHomepage' => null,
        'siteSearchEnabled' => true,
        'tinyMceApiKey' => '',
        'tinyMceConfig' => [
            'selector' => '#bodyContent',
            'block_formats' => 'Paragraph=p; Header 1=h1; Header 2=h2; Header 3=h3',
            'plugins' => implode(' ', [
                'a11ychecker',
                'advlist',
                'advcode',
                'advtable',
                'autolink',
                'casechange',
                'checklist',
                'export',
                'formatpainter',
                'image',
                'imagetools',
                'link',
                'linkchecker',
                'lists',
                'media',
                'mediaembed',
                'pageembed',
                'powerpaste',
                'table',
                'tinymcespellchecker',
                'wordcount',
            ]),
            'toolbar' => [
                [
                    'name' => 'history',
                    'items' => [
                        'undo',
                        'redo',
                    ],
                ],
                [
                    'name' => 'styles',
                    'items' => [
                        'styleselect',
                    ],
                ],
                [
                    'name' => 'formatting',
                    'items' => [
                        'bold',
                        'italic',
                        'underline',
                        'strikethrough',
                        'subscript',
                        'superscript',
                    ],
                ],
                [
                    'name' => 'alignment',
                    'items' => [
                        'alignnone',
                        'alignleft',
                        'aligncenter',
                        'alignright',
                        'alignjustify',
                    ],
                ],
                [
                    'name' => 'lists',
                    'items' => [
                        'bullist',
                        'numlist',
                    ],
                ],
                [
                    'name' => 'indentation',
                    'items' => [
                        'outdent',
                        'indent',
                    ],
                ],
                [
                    'name' => 'media',
                    'items' => [
                        'link',
                        'unlink',
                        'blockquote',
                        'image',
                        'table',
                        'pageembed',
                    ],
                ],
                [
                    'name' => 'tools',
                    'items' => [
                        'casechange',
                        'checklist',
                        'code',
                    ],
                ],
            ],
            'toolbar_mode' => 'floating',
            'images_upload_url' => '/admin/media/create?tinymce=true',
            'automatic_uploads' => true,
        ],
    ],
];
