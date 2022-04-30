<?php

declare(strict_types=1);

namespace Support\System\Domain\I18n\Bus\Query;

use Doctrine\ORM\EntityManagerInterface;
use Support\System\Domain\I18n\UsedLocale;

final class GetUsedLocaleBySlugHandler
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager
    ) {
    }

    public function __invoke(GetUsedLocaleBySlug $query): ?UsedLocale
    {
        $qb = $this->entityManager->createQueryBuilder();
        $qb->select('e');
        $qb->from(UsedLocale::class, 'e');
        $qb->where($qb->expr()->eq('e.slug', ':slug'));
        $qb->setParameter('slug', $query->slug);
        $qb->setMaxResults(1);

        return $qb->getQuery()->getOneOrNullResult();
    }
}
