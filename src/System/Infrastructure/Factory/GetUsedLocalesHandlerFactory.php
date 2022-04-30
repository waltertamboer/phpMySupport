<?php

declare(strict_types=1);

namespace Support\System\Infrastructure\Factory;

use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;
use Support\System\Domain\I18n\Bus\Query\GetUsedLocalesHandler;

final class GetUsedLocalesHandlerFactory
{
    public function __invoke(ContainerInterface $container): GetUsedLocalesHandler
    {
        return new GetUsedLocalesHandler(
            $container->get(EntityManagerInterface::class),
        );
    }
}
