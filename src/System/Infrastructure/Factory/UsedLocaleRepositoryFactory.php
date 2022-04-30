<?php

declare(strict_types=1);

namespace Support\System\Infrastructure\Factory;

use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;
use Support\System\Infrastructure\Doctrine\ORM\UsedLocaleRepository;

final class UsedLocaleRepositoryFactory
{
    public function __invoke(ContainerInterface $container): UsedLocaleRepository
    {
        return new UsedLocaleRepository(
            $container->get(EntityManagerInterface::class),
        );
    }
}
