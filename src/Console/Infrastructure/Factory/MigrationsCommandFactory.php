<?php

declare(strict_types=1);

namespace Support\Console\Infrastructure\Factory;

use Doctrine\Migrations\Configuration\Migration\ConfigurationArray;
use Doctrine\Migrations\DependencyFactory;
use Interop\Container\ContainerInterface;
use Support\Console\Infrastructure\Doctrine\Migrations\EntityManagerContainer;

final class MigrationsCommandFactory
{
    /**
     * @return mixed
     */
    public function __invoke(ContainerInterface $serviceLocator, string $requestedName)
    {
        $config = $serviceLocator->get('config');

        $migrationConfig = $config['doctrine']['migrations'] ?? [];
        unset($migrationConfig['dependency_factory_services']);

        $dependencyFactory = DependencyFactory::fromEntityManager(
            new ConfigurationArray($migrationConfig),
            new EntityManagerContainer($serviceLocator)
        );

        return new $requestedName($dependencyFactory);
    }
}
