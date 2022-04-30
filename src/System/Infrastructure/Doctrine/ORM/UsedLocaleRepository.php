<?php

declare(strict_types=1);

namespace Support\System\Infrastructure\Doctrine\ORM;

use Doctrine\ORM\EntityManagerInterface;
use Support\System\Domain\I18n\UsedLocale;
use Support\System\Domain\I18n\UsedLocaleList;
use Support\System\Domain\I18n\UsedLocaleRepository as BaseUsedLocaleRepository;

final class UsedLocaleRepository implements BaseUsedLocaleRepository
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function getUsedLocales(): UsedLocaleList
    {
        $qb = $this->entityManager->createQueryBuilder();
        $qb->select('e');
        $qb->from(UsedLocale::class, 'e');

        $items = $qb->getQuery()->getResult();

        $result = new UsedLocaleList();

        foreach ($items as $item) {
            $result->add($item);
        }

        return $result;
    }

    public function find(string $id): ?UsedLocale
    {
        $qb = $this->entityManager->createQueryBuilder();
        $qb->select('e');
        $qb->from(UsedLocale::class, 'e');
        $qb->where($qb->expr()->eq('e.id', ':id'));
        $qb->setParameter('id', $id);

        return $qb->getQuery()->getOneOrNullResult();
    }
}
