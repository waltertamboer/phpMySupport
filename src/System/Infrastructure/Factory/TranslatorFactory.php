<?php

declare(strict_types=1);

namespace Support\System\Infrastructure\Factory;

use Psr\Container\ContainerInterface;
use Support\System\Domain\I18n\Translator;
use Support\System\Infrastructure\I18n\SymfonyTranslator;
use Symfony\Contracts\Translation\TranslatorInterface;

final class TranslatorFactory
{
    public function __invoke(ContainerInterface $container): Translator
    {
        return new SymfonyTranslator(
            $container->get(TranslatorInterface::class),
        );
    }
}
