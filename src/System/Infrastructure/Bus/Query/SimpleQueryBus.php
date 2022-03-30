<?php

declare(strict_types=1);

namespace Support\System\Infrastructure\Bus\Query;

use Psr\Container\ContainerInterface;
use Support\System\Domain\Bus\Query\QueryBus;
use Support\System\Domain\Value\StringStringMap;

final class SimpleQueryBus implements QueryBus
{
    public function __construct(
        private readonly ContainerInterface $handlerContainer,
        private readonly StringStringMap $handlers = new StringStringMap(),
    ) {
    }

    /**
     * @inheritDoc
     */
    public function query($query)
    {
        $fqcn = get_class($query);

        $handlerId = $this->handlers->get($fqcn);

        $handler = $this->handlerContainer->get($handlerId);

        return $handler($query);
    }
}
