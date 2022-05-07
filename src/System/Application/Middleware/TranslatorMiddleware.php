<?php

declare(strict_types=1);

namespace Support\System\Application\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Support\System\Domain\I18n\Translator;

final class TranslatorMiddleware implements MiddlewareInterface
{
    public const NAME = 'translator';

    public function __construct(
        private readonly Translator $translator
    ) {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler) : ResponseInterface
    {
        $usedLocale = $request->getAttribute(LocalizationMiddleware::USED_LOCALIZATION_ATTRIBUTE);

        if ($usedLocale !== null) {
            $this->translator->setLocale($usedLocale->getSlug());
        }

        return $handler->handle($request->withAttribute(self::NAME, $this->translator));
    }
}
