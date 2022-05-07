<?php

declare(strict_types=1);

namespace Support\System\Infrastructure\Factory;

use DirectoryIterator;
use Psr\Container\ContainerInterface;
use Symfony\Component\Translation\Loader\JsonFileLoader;
use Symfony\Component\Translation\Translator;

final class SymfonyTranslatorFactory
{
    public function __invoke(ContainerInterface $container): Translator
    {
        $translator = new Translator('en_US');
        $translator->addLoader('json', new JsonFileLoader());
        $translator->setFallbackLocales(['en_US']);

        $this->populateTranslator($translator);

        return $translator;
    }

    private function populateTranslator(Translator $translator): void
    {
        $iterator = new DirectoryIterator('data/translations/');

        foreach ($iterator as $fileItem) {
            if ($fileItem->getExtension() !== 'json') {
                continue;
            }

            $locale = $fileItem->getBasename('.json');

            $translator->addResource('json', $fileItem->getPathname(), $locale);
        }
    }
}
