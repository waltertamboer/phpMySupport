<?php

declare(strict_types=1);

namespace Support\KnowledgeBase\Infrastructure\Factory;

use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;
use Support\KnowledgeBase\Infrastructure\Doctrine\ORM\CategoryRepository;

final class CategoryRepositoryFactory
{
    public function __invoke(ContainerInterface $container): CategoryRepository
    {
        return new CategoryRepository(
            $container->get(EntityManagerInterface::class),
        );
    }
}
