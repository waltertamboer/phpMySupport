<?php

declare(strict_types=1);

namespace Support\KnowledgeBase\Infrastructure\Factory;

use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;
use Support\KnowledgeBase\Application\RequestHandler\Article;
use Support\KnowledgeBase\Infrastructure\Doctrine\ORM\ArticleRepository;

final class ArticleRepositoryFactory
{
    public function __invoke(ContainerInterface $container): Article
    {
        return new ArticleRepository(
            $container->get(EntityManagerInterface::class),
        );
    }
}
