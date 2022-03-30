<?php

declare(strict_types=1);

namespace Support\KnowledgeBase\Infrastructure\Doctrine\ORM;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Support\KnowledgeBase\Domain\Article\Article;
use Support\KnowledgeBase\Domain\Article\ArticleCollection;
use Support\KnowledgeBase\Domain\Article\ArticleRepository as BaseArticleRepository;
use Support\System\Domain\Criteria\Criteria;

final class ArticleRepository implements BaseArticleRepository
{
    private readonly EntityRepository $entityRepository;

    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    ) {
        $this->entityRepository = $this->entityManager->getRepository(Article::class);
    }

    public function query(Criteria $criteria): ArticleCollection
    {
    }

    public function save(Article $article): void
    {
        $this->entityManager->persist($article);
        $this->entityManager->flush();
    }
}
