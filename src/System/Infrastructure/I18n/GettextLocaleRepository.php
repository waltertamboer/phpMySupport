<?php

declare(strict_types=1);

namespace Support\System\Infrastructure\I18n;

use Doctrine\ORM\EntityManagerInterface;
use Gettext\Languages\Language;
use Support\KnowledgeBase\Domain\Article\Article;
use Support\KnowledgeBase\Domain\Category\Category;
use Support\System\Domain\I18n\Locale;
use Support\System\Domain\I18n\LocaleList;
use Support\System\Domain\I18n\LocaleRepository;

final class GettextLocaleRepository implements LocaleRepository
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function getUsedLocales(): LocaleList
    {
        $qb = $this->entityManager->createQueryBuilder();
        $qb->select('r.locale');
        $qb->from(Category::class, 'e');
        $qb->join('e.lastRevision', 'r');
        $qb->groupBy('r.locale');

        $items = $qb->getQuery()->getResult();

        $result = new LocaleList();

        foreach ($items as $item) {
            $result->add($this->lookup($item['locale']));
        }

        return $result;
    }

    public function lookup(string $id): ?Locale
    {
        $language = Language::getById($id);

        if ($language === null) {
            return null;
        }

        return new Locale($language->id, $language->name);
    }

    public function query(string $query): LocaleList
    {
        $languages = Language::getAll();
        $languages = array_filter($languages, static function (Language $item) use ($query) {
            return strpos(strtolower($item->name), strtolower($query)) !== false;
        });
        $languages = array_values($languages);
        $languages = array_map(static function (Language $language): Locale {
            return new Locale($language->id, $language->name);
        }, $languages);

        return new LocaleList($languages);
    }
}
