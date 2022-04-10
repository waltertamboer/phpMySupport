<?php

declare(strict_types=1);

namespace Support\System\Infrastructure\Factory;

use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;
use Support\System\Infrastructure\Doctrine\ORM\SettingManager;

final class SettingManagerFactory
{
    public function __invoke(ContainerInterface $container): SettingManager
    {
        return new SettingManager(
            $container->get(EntityManagerInterface::class),
        );
    }
}
