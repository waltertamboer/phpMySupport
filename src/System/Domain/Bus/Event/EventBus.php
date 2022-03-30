<?php

declare(strict_types=1);

namespace Support\System\Domain\Bus\Event;

interface EventBus
{
    public function publish(Event ...$events): void;
}
