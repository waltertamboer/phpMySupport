<?php

declare(strict_types=1);

namespace Support\Console\Infrastructure\Factory;

use ArrayObject;
use Doctrine\Migrations\Tools\Console\Command\CurrentCommand;
use Doctrine\Migrations\Tools\Console\Command\DiffCommand;
use Doctrine\Migrations\Tools\Console\Command\DumpSchemaCommand;
use Doctrine\Migrations\Tools\Console\Command\ExecuteCommand;
use Doctrine\Migrations\Tools\Console\Command\GenerateCommand;
use Doctrine\Migrations\Tools\Console\Command\LatestCommand;
use Doctrine\Migrations\Tools\Console\Command\ListCommand;
use Doctrine\Migrations\Tools\Console\Command\MigrateCommand;
use Doctrine\Migrations\Tools\Console\Command\RollupCommand;
use Doctrine\Migrations\Tools\Console\Command\StatusCommand;
use Doctrine\Migrations\Tools\Console\Command\SyncMetadataCommand;
use Doctrine\Migrations\Tools\Console\Command\UpToDateCommand;
use Doctrine\Migrations\Tools\Console\Command\VersionCommand;
use Doctrine\ORM\Tools\Console\Command\ClearCache\CollectionRegionCommand;
use Doctrine\ORM\Tools\Console\Command\ClearCache\EntityRegionCommand;
use Doctrine\ORM\Tools\Console\Command\ClearCache\MetadataCommand;
use Doctrine\ORM\Tools\Console\Command\ClearCache\QueryCommand;
use Doctrine\ORM\Tools\Console\Command\ClearCache\QueryRegionCommand;
use Doctrine\ORM\Tools\Console\Command\ClearCache\ResultCommand;
use Doctrine\ORM\Tools\Console\Command\GenerateProxiesCommand;
use Doctrine\ORM\Tools\Console\Command\InfoCommand;
use Doctrine\ORM\Tools\Console\Command\MappingDescribeCommand;
use Doctrine\ORM\Tools\Console\Command\RunDqlCommand;
use Doctrine\ORM\Tools\Console\Command\SchemaTool\CreateCommand;
use Doctrine\ORM\Tools\Console\Command\SchemaTool\DropCommand;
use Doctrine\ORM\Tools\Console\Command\SchemaTool\UpdateCommand;
use Doctrine\ORM\Tools\Console\Command\ValidateSchemaCommand;
use Psr\Container\ContainerInterface;
use Support\Console\Application\Command\CreateUser;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\CommandLoader\ContainerCommandLoader;

final class ApplicationFactory
{
    public function __invoke(ContainerInterface $container): Application
    {
        $commandMap = new ArrayObject();

        $this->addDoctrineMigrationsCommands($commandMap);
        $this->addDoctrineORMCommands($commandMap);
        $this->addUserCommands($commandMap);

        $application = new Application();
        $application->setCommandLoader(new ContainerCommandLoader($container, $commandMap->getArrayCopy()));

        return $application;
    }

    private function addDoctrineMigrationsCommands(ArrayObject $commandMap): void
    {
        $commandMap['migrations:current'] = CurrentCommand::class;
        $commandMap['migrations:diff'] = DiffCommand::class;
        $commandMap['migrations:dump-schema'] = DumpSchemaCommand::class;
        $commandMap['migrations:execute'] = ExecuteCommand::class;
        $commandMap['migrations:generate'] = GenerateCommand::class;
        $commandMap['migrations:latest'] = LatestCommand::class;
        $commandMap['migrations:list'] = ListCommand::class;
        $commandMap['migrations:migrate'] = MigrateCommand::class;
        $commandMap['migrations:rollup'] = RollupCommand::class;
        $commandMap['migrations:status'] = StatusCommand::class;
        $commandMap['migrations:sync-metadata-storage'] = SyncMetadataCommand::class;
        $commandMap['migrations:up-to-date'] = UpToDateCommand::class;
        $commandMap['migrations:version'] = VersionCommand::class;
    }

    private function addDoctrineORMCommands(ArrayObject $commandMap): void
    {
        // Clear Cache
        $commandMap['orm:clear-cache:region:collection'] = CollectionRegionCommand::class;
        $commandMap['orm:clear-cache:region:entity'] = EntityRegionCommand::class;
        $commandMap['orm:clear-cache:metadata'] = MetadataCommand::class;
        $commandMap['orm:clear-cache:query'] = QueryCommand::class;
        $commandMap['orm:clear-cache:region:query'] = QueryRegionCommand::class;
        $commandMap['orm:clear-cache:result'] = ResultCommand::class;

        // Schema Tool
        $commandMap['orm:schema-tool:create'] = CreateCommand::class;
        $commandMap['orm:schema-tool:drop'] = DropCommand::class;
        $commandMap['orm:schema-tool:update'] = UpdateCommand::class;

        $commandMap['orm:generate-proxies'] = GenerateProxiesCommand::class;
        $commandMap['orm:info'] = InfoCommand::class;
        $commandMap['orm:mapping:describe'] = MappingDescribeCommand::class;
        $commandMap['orm:run-dql'] = RunDqlCommand::class;
        $commandMap['orm:validate-schema'] = ValidateSchemaCommand::class;
    }

    private function addUserCommands(ArrayObject $commandMap): void
    {
        $commandMap['user:create'] = CreateUser::class;
    }
}
