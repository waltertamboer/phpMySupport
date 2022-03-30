<?php

declare(strict_types=1);

namespace Support\Console\Infrastructure\Factory;

use Interop\Container\ContainerInterface;
use Support\Console\Infrastructure\Doctrine\Migrations\EntityManagerContainer;

/**
 * Service factory for migrations command
 */
final class OrmCommandFactory
{
    /**
     * @return mixed
     */
    public function __invoke(ContainerInterface $serviceLocator, string $requestedName) // phpcs:ignore
    {
        $entityManagerProvider = new EntityManagerContainer($serviceLocator);

        return new $requestedName($entityManagerProvider);
    }
}
