<?php

declare(strict_types=1);

namespace Support\KnowledgeBase\Infrastructure\Factory;

use Psr\Container\ContainerInterface;
use Support\KnowledgeBase\Domain\Category\Bus\Query\FindCategoryBySlugHandler;
use Support\KnowledgeBase\Domain\Category\CategoryRepository;

final class FindCategoryBySlugHandlerFactory
{
    public function __invoke(ContainerInterface $container): FindCategoryBySlugHandler
    {
        return new FindCategoryBySlugHandler(
            $container->get(CategoryRepository::class),
        );
    }
}
