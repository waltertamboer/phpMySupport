<?php

declare(strict_types=1);

namespace Support\System\Infrastructure\Factory;

use Psr\Container\ContainerInterface;
use Support\System\Domain\Bus\Command\CommandBus;
use Support\System\Domain\Value\StringStringMap;
use Support\System\Infrastructure\Bus\Command\SimpleCommandBus;

final class CommandBusFactory
{
    public function __invoke(ContainerInterface $container): CommandBus
    {
        $config = $container->get('config');

        if (!array_key_exists('command_bus', $config) || !is_array($config['command_bus'])) {
            return new SimpleCommandBus($container, new StringStringMap());
        }

        return new SimpleCommandBus($container, new StringStringMap($config['command_bus']));
    }
}
