<?php

declare(strict_types=1);

namespace Support\KnowledgeBase\Domain\Category\Bus\Query;

use Doctrine\ORM\EntityManagerInterface;
use Support\KnowledgeBase\Domain\Article\Article;

final class FindArticlesForCategoryHandler
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    public function __invoke(FindArticlesForCategory $query): array
    {
        $qb = $this->entityManager->createQueryBuilder();
        $qb->select('a');
        $qb->from(Article::class, 'a');
        $qb->join('a.lastRevision', 'r');
        $qb->where($qb->expr()->isMemberOf(':category', 'r.categories'));
        $qb->setParameter('category', $query->category);

        return $qb->getQuery()->getResult();
    }
}
