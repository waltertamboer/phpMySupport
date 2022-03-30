<?php

declare(strict_types=1);

namespace Support\System\Application\Exception;

use Psr\Http\Message\RequestInterface;
use RuntimeException;
use Throwable;

final class ResourceNotFound extends RuntimeException
{
    private function __construct(
        public readonly RequestInterface $request,
        string $message,
        int $code,
        ?Throwable $previous
    ) {
        parent::__construct($message, $code, $previous);
    }

    public static function fromRequest(RequestInterface $request): ResourceNotFound
    {
        return new static($request, 'Resource not found', 404, null);
    }
}
