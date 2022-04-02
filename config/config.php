<?php

declare(strict_types=1);

use Laminas\ConfigAggregator\ArrayProvider;
use Laminas\ConfigAggregator\ConfigAggregator;
use Laminas\ConfigAggregator\PhpFileProvider;

// To enable or disable caching, set the `ConfigAggregator::ENABLE_CACHE` boolean in
// `config/autoload/local.php`.
$cacheConfig = [
    'config_cache_path' => 'data/cache/config-cache.php',
];

$aggregator = new ConfigAggregator([
    // Cache
    new ArrayProvider($cacheConfig),

    // Libraries
    \Mezzio\ConfigProvider::class,
    \Mezzio\Authentication\ConfigProvider::class,
    \Mezzio\Authentication\Session\ConfigProvider::class,
    \Mezzio\Authorization\ConfigProvider::class,
    \Mezzio\Authorization\Rbac\ConfigProvider::class,
    \Mezzio\Cors\ConfigProvider::class,
    \Mezzio\Csrf\ConfigProvider::class,
    \Mezzio\Flash\ConfigProvider::class,
    \Mezzio\Helper\ConfigProvider::class,
    \Mezzio\ProblemDetails\ConfigProvider::class,
    \Mezzio\Router\ConfigProvider::class,
    \Mezzio\Router\FastRouteRouter\ConfigProvider::class,
    \Mezzio\Session\ConfigProvider::class,
    \Mezzio\Session\Ext\ConfigProvider::class,
    \Mezzio\Twig\ConfigProvider::class,
    \Laminas\Diactoros\ConfigProvider::class,

    // Application
    \Support\Admin\Infrastructure\Laminas\ConfigProvider::class,
    \Support\Console\Infrastructure\Laminas\ConfigProvider::class,
    \Support\KnowledgeBase\Infrastructure\Laminas\ConfigProvider::class,
    \Support\System\Infrastructure\Laminas\ConfigProvider::class,

    // Overrides
    new PhpFileProvider(realpath(__DIR__) . '/autoload/{{,*.}global,{,*.}local}.php'),
    new PhpFileProvider(realpath(__DIR__) . '/development.config.php'),
    new \Support\System\Infrastructure\Laminas\ConfigAggregator\EnvironmentProvider(),
], $cacheConfig['config_cache_path']);

return $aggregator->getMergedConfig();
