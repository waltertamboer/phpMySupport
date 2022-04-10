<?php

declare(strict_types=1);

namespace Support\System\Infrastructure\Factory;

use Psr\Container\ContainerInterface;
use Support\System\Application\Middleware\SettingsMiddleware;
use Support\System\Domain\SettingManager;

final class SettingsMiddlewareFactory
{
    public function __invoke(ContainerInterface $container): SettingsMiddleware
    {
        $settingManager = $container->get(SettingManager::class);

        return new SettingsMiddleware($settingManager);
    }
}
