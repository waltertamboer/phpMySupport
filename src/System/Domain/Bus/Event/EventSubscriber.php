<?php

declare(strict_types=1);

namespace Support\System\Domain\Bus\Event;

interface EventSubscriber
{
    public static function subscribedTo(): array;
}
