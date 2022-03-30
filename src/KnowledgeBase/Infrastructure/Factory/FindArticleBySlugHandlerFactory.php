<?php

declare(strict_types=1);

namespace Support\KnowledgeBase\Infrastructure\Factory;

use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;
use Support\KnowledgeBase\Domain\Article\Bus\Query\FindArticleBySlugHandler;

final class FindArticleBySlugHandlerFactory
{
    public function __invoke(ContainerInterface $container): FindArticleBySlugHandler
    {
        return new FindArticleBySlugHandler(
            $container->get(EntityManagerInterface::class),
        );
    }
}
