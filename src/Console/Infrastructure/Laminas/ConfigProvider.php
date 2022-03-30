<?php

declare(strict_types=1);

namespace Support\Console\Infrastructure\Laminas;

use Laminas\ServiceManager\Factory\InvokableFactory;
use Support\Admin\Application\RequestHandler;
use Support\Admin\Infrastructure\Factory;
use Support\Console\Application\Application;
use Support\Console\Infrastructure\Factory\ApplicationFactory;
use Support\Console\Infrastructure\Factory\CreateUserCommandFactory;
use Support\Console\Infrastructure\Factory\MigrationsCommandFactory;
use Support\Console\Infrastructure\Factory\OrmCommandFactory;
use Support\Console\Application\Command;

final class ConfigProvider
{
    public function __invoke(): array
    {
        return [
            'dependencies' => $this->getDependencies(),
        ];
    }

    private function getDependencies(): array
    {
        // phpcs:disable
        return [
            'factories' => [
                Application::class => ApplicationFactory::class,

                // User commands
                Command\CreateUser::class => CreateUserCommandFactory::class,

                // Doctrine migrations
                \Doctrine\Migrations\Tools\Console\Command\CurrentCommand::class => MigrationsCommandFactory::class,
                \Doctrine\Migrations\Tools\Console\Command\DiffCommand::class => MigrationsCommandFactory::class,
                \Doctrine\Migrations\Tools\Console\Command\DumpSchemaCommand::class => MigrationsCommandFactory::class,
                \Doctrine\Migrations\Tools\Console\Command\ExecuteCommand::class => MigrationsCommandFactory::class,
                \Doctrine\Migrations\Tools\Console\Command\GenerateCommand::class => MigrationsCommandFactory::class,
                \Doctrine\Migrations\Tools\Console\Command\LatestCommand::class => MigrationsCommandFactory::class,
                \Doctrine\Migrations\Tools\Console\Command\ListCommand::class => MigrationsCommandFactory::class,
                \Doctrine\Migrations\Tools\Console\Command\MigrateCommand::class => MigrationsCommandFactory::class,
                \Doctrine\Migrations\Tools\Console\Command\RollupCommand::class => MigrationsCommandFactory::class,
                \Doctrine\Migrations\Tools\Console\Command\StatusCommand::class => MigrationsCommandFactory::class,
                \Doctrine\Migrations\Tools\Console\Command\SyncMetadataCommand::class => MigrationsCommandFactory::class,
                \Doctrine\Migrations\Tools\Console\Command\UpToDateCommand::class => MigrationsCommandFactory::class,
                \Doctrine\Migrations\Tools\Console\Command\VersionCommand::class => MigrationsCommandFactory::class,

                // Doctrine ORM
                \Doctrine\ORM\Tools\Console\Command\ClearCache\CollectionRegionCommand::class => OrmCommandFactory::class,
                \Doctrine\ORM\Tools\Console\Command\ClearCache\EntityRegionCommand::class => OrmCommandFactory::class,
                \Doctrine\ORM\Tools\Console\Command\ClearCache\MetadataCommand::class => OrmCommandFactory::class,
                \Doctrine\ORM\Tools\Console\Command\ClearCache\QueryCommand::class => OrmCommandFactory::class,
                \Doctrine\ORM\Tools\Console\Command\ClearCache\QueryRegionCommand::class => OrmCommandFactory::class,
                \Doctrine\ORM\Tools\Console\Command\ClearCache\ResultCommand::class => OrmCommandFactory::class,
                \Doctrine\ORM\Tools\Console\Command\SchemaTool\CreateCommand::class => OrmCommandFactory::class,
                \Doctrine\ORM\Tools\Console\Command\SchemaTool\DropCommand::class => OrmCommandFactory::class,
                \Doctrine\ORM\Tools\Console\Command\SchemaTool\UpdateCommand::class => OrmCommandFactory::class,
                \Doctrine\ORM\Tools\Console\Command\GenerateProxiesCommand::class => OrmCommandFactory::class,
                \Doctrine\ORM\Tools\Console\Command\InfoCommand::class => OrmCommandFactory::class,
                \Doctrine\ORM\Tools\Console\Command\MappingDescribeCommand::class => OrmCommandFactory::class,
                \Doctrine\ORM\Tools\Console\Command\RunDqlCommand::class => OrmCommandFactory::class,
                \Doctrine\ORM\Tools\Console\Command\ValidateSchemaCommand::class => OrmCommandFactory::class,
            ],
        ];
        // phpcs:enable
    }
}
