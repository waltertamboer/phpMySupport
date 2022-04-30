<?php

declare(strict_types=1);

namespace Support\System\Infrastructure\Factory;

use Psr\Container\ContainerInterface;
use Support\System\Domain\I18n\LocaleQueryRepository;
use Support\System\Infrastructure\I18n\GettextLocaleQueryRepository;

final class LocaleQueryRepositoryFactory
{
    public function __invoke(ContainerInterface $container): LocaleQueryRepository
    {
        return new GettextLocaleQueryRepository();
    }
}
