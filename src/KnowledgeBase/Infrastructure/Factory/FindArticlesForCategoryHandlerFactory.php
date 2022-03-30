<?php

declare(strict_types=1);

namespace Support\KnowledgeBase\Infrastructure\Factory;

use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;
use Support\KnowledgeBase\Domain\Category\Bus\Query\FindArticlesForCategoryHandler;

final class FindArticlesForCategoryHandlerFactory
{
    public function __invoke(ContainerInterface $container): FindArticlesForCategoryHandler
    {
        return new FindArticlesForCategoryHandler(
            $container->get(EntityManagerInterface::class),
        );
    }
}
