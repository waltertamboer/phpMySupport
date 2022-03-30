<?php

declare(strict_types=1);

namespace Support\KnowledgeBase\Domain\Article\Bus\Query;

use Doctrine\ORM\EntityManagerInterface;
use Support\KnowledgeBase\Domain\Article\Article;

final class FindArticleBySlugHandler
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager
    ) {
    }

    public function __invoke(FindArticleBySlug $query): ?Article
    {
        $qb = $this->entityManager->createQueryBuilder();
        $qb->select('a');
        $qb->from(Article::class, 'a');
        $qb->join('a.lastRevision', 'r');
        $qb->where($qb->expr()->eq('r.slug', ':slug'));
        $qb->setParameter('slug', $query->slug);
        $qb->setMaxResults(1);

        return $qb->getQuery()->getOneOrNullResult();
    }
}
