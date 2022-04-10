<?php

declare(strict_types=1);

namespace Support\Admin\Infrastructure\Factory\Settings;

use Psr\Container\ContainerInterface;
use Support\Admin\Application\RequestHandler\Settings\Locales;
use Support\System\Domain\I18n\LocaleRepository;

final class LocalesRequestHandlerFactory
{
    public function __invoke(ContainerInterface $container): Locales
    {
        return new Locales(
            $container->get(LocaleRepository::class),
        );
    }
}
