<?php

declare(strict_types=1);

namespace Support\System\Infrastructure\Factory;

use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;
use Support\System\Domain\I18n\Bus\Query\GetUsedLocaleBySlugHandler;

final class GetUsedLocaleBySlugHandlerFactory
{
    public function __invoke(ContainerInterface $container): GetUsedLocaleBySlugHandler
    {
        return new GetUsedLocaleBySlugHandler(
            $container->get(EntityManagerInterface::class),
        );
    }
}
