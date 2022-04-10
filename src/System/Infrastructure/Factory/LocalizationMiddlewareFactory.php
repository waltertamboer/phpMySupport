<?php

declare(strict_types=1);

namespace Support\System\Infrastructure\Factory;

use Psr\Container\ContainerInterface;
use Support\System\Application\Middleware\LocalizationMiddleware;
use Support\System\Domain\SettingManager;

final class LocalizationMiddlewareFactory
{
    public function __invoke(ContainerInterface $container): LocalizationMiddleware
    {
        $settingManager = $container->get(SettingManager::class);

        return new LocalizationMiddleware($settingManager);
    }
}
