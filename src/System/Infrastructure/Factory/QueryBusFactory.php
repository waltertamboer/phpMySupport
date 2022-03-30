<?php

declare(strict_types=1);

namespace Support\System\Infrastructure\Factory;

use Psr\Container\ContainerInterface;
use Support\System\Domain\Bus\Query\QueryBus;
use Support\System\Domain\Value\StringStringMap;
use Support\System\Infrastructure\Bus\Query\SimpleQueryBus;

final class QueryBusFactory
{
    public function __invoke(ContainerInterface $container): QueryBus
    {
        $config = $container->get('config');

        if (!array_key_exists('query_bus', $config) || !is_array($config['query_bus'])) {
            return new SimpleQueryBus($container, new StringStringMap());
        }

        return new SimpleQueryBus($container, new StringStringMap($config['query_bus']));
    }
}
