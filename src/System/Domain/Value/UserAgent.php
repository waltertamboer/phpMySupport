<?php

declare(strict_types=1);

namespace Support\System\Domain\Value;

use Psr\Http\Message\ServerRequestInterface;

class UserAgent
{
    public function __construct(
        public readonly string $userAgent
    ) {
    }

    public static function fromRequest(ServerRequestInterface $request): ?UserAgent
    {
        $params = $request->getServerParams();

        if (!array_key_exists('HTTP_USER_AGENT', $params)) {
            return null;
        }

        return new UserAgent($params['HTTP_USER_AGENT']);
    }
}
