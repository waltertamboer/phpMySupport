<?php

declare(strict_types=1);

namespace Support\KnowledgeBase\Infrastructure\Factory;

use Psr\Container\ContainerInterface;
use Support\KnowledgeBase\Domain\Category\Bus\Query\GetCategoryOverviewHandler;
use Support\KnowledgeBase\Domain\Category\CategoryRepository;

final class GetCategoryOverviewHandlerFactory
{
    public function __invoke(ContainerInterface $container): GetCategoryOverviewHandler
    {
        return new GetCategoryOverviewHandler(
            $container->get(CategoryRepository::class),
        );
    }
}
