<?php

declare(strict_types=1);

namespace Support\System\Application\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Symfony\Component\Translation\Translator;

final class TranslatorMiddleware implements MiddlewareInterface
{
    public const NAME = 'translator';

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler) : ResponseInterface
    {
        $translator = new Translator('en');

        return $handler->handle($request->withAttribute(self::NAME, $translator));
    }
}
