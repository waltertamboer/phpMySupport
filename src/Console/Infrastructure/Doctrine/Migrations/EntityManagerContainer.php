<?php

declare(strict_types=1);

namespace Support\Console\Infrastructure\Doctrine\Migrations;

use Doctrine\Migrations\Configuration\EntityManager\EntityManagerLoader;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Console\EntityManagerProvider;
use Psr\Container\ContainerInterface;

final class EntityManagerContainer implements EntityManagerLoader, EntityManagerProvider
{
    private ContainerInterface $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function getEntityManager(?string $name = null): EntityManagerInterface
    {
        if ($name === null || $name === '') {
            return $this->getDefaultManager();
        }

        return $this->container->get($name);
    }

    public function getDefaultManager(): EntityManagerInterface
    {
        return $this->container->get(EntityManagerInterface::class);
    }

    public function getManager(string $name): EntityManagerInterface
    {
        return $this->getEntityManager($name);
    }
}
