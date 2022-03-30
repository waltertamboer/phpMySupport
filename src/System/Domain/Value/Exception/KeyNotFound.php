<?php

declare(strict_types=1);

namespace Support\System\Domain\Value\Exception;

use InvalidArgumentException;

use function sprintf;

final class KeyNotFound extends InvalidArgumentException
{
    public static function fromKey(string $key): KeyNotFound
    {
        $msg = sprintf('The key "%s" could not be found.', $key);

        return new static($msg);
    }
}
