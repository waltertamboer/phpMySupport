<?php

declare(strict_types=1);

namespace Support\Admin\Infrastructure\Factory\Settings;

use Psr\Container\ContainerInterface;
use Support\Admin\Application\RequestHandler\Settings\LocalesXhr;
use Support\System\Domain\I18n\LocaleQueryRepository;

final class LocalesXhrRequestHandlerFactory
{
    public function __invoke(ContainerInterface $container): LocalesXhr
    {
        return new LocalesXhr(
            $container->get(LocaleQueryRepository::class),
        );
    }
}
