<?php

declare(strict_types=1);

namespace Support\System\Infrastructure\Factory;

use Psr\Container\ContainerInterface;
use Support\System\Application\Middleware\LocalizationMiddleware;
use Support\System\Domain\I18n\UsedLocaleRepository;
use Support\System\Domain\SettingManager;

final class LocalizationMiddlewareFactory
{
    public function __invoke(ContainerInterface $container): LocalizationMiddleware
    {
        return new LocalizationMiddleware(
            $container->get(SettingManager::class),
            $container->get(UsedLocaleRepository::class),
        );
    }
}
