<?php

declare(strict_types=1);

namespace Support\System\Domain\I18n\Bus\Query;

use Doctrine\ORM\EntityManagerInterface;
use Support\System\Domain\I18n\UsedLocale;
use Support\System\Domain\I18n\UsedLocaleList;

final class GetUsedLocalesHandler
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager
    ) {
    }

    public function __invoke(GetUsedLocales $query): UsedLocaleList
    {
        $qb = $this->entityManager->createQueryBuilder();
        $qb->select('e');
        $qb->from(UsedLocale::class, 'e');

        $result = new UsedLocaleList();

        foreach ($qb->getQuery()->getResult() as $item) {
            $result->add($item);
        }

        return $result;
    }
}
