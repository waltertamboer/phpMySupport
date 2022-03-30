<?php

declare(strict_types=1);

namespace Support\System\Domain\Value;

use Psr\Http\Message\ServerRequestInterface;

class IPAddress
{
    public function __construct(
        public readonly string $address
    ) {
    }

    public static function fromRequest(ServerRequestInterface $request): ?IPAddress
    {
        $params = $request->getServerParams();

        if (!array_key_exists('REMOTE_ADDR', $params)) {
            return null;
        }

        return new IPAddress($params['REMOTE_ADDR']);
    }
}
