<?php

declare(strict_types=1);

namespace Support\System\Infrastructure\Factory;

use Psr\Container\ContainerInterface;
use Support\System\Domain\I18n\LocaleRepository;
use Support\System\Infrastructure\I18n\GettextLocaleRepository;

final class LocaleRepositoryFactory
{
    public function __invoke(ContainerInterface $container): LocaleRepository
    {
        return new GettextLocaleRepository();
    }
}
