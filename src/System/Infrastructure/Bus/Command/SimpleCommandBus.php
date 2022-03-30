<?php

declare(strict_types=1);

namespace Support\System\Infrastructure\Bus\Command;

use Psr\Container\ContainerInterface;
use Support\System\Domain\Bus\Command\Command;
use Support\System\Domain\Bus\Command\CommandBus;
use Support\System\Domain\Value\StringStringMap;

final class SimpleCommandBus implements CommandBus
{
    public function __construct(
        private readonly ContainerInterface $handlerContainer,
        private readonly StringStringMap $handlers = new StringStringMap(),
    ) {
    }

    /**
     * @inheritDoc
     */
    public function dispatch(Command $command): void
    {
        $fqcn = get_class($command);

        $handlerId = $this->handlers->get($fqcn);

        $handler = $this->handlerContainer->get($handlerId);
        $handler($command);
    }
}
