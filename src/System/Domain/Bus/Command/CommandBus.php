<?php

declare(strict_types=1);

namespace Support\System\Domain\Bus\Command;

interface CommandBus
{
    public function dispatch(Command $command): void;
}
