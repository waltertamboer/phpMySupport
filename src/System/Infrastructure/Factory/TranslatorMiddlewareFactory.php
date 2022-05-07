<?php

declare(strict_types=1);

namespace Support\System\Infrastructure\Factory;

use Psr\Container\ContainerInterface;
use Support\System\Application\Middleware\TranslatorMiddleware;
use Support\System\Domain\I18n\Translator;

final class TranslatorMiddlewareFactory
{
    public function __invoke(ContainerInterface $container): TranslatorMiddleware
    {
        $translator = $container->get(Translator::class);

        return new TranslatorMiddleware($translator);
    }
}
