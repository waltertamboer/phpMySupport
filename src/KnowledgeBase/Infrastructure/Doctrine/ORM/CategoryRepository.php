<?php

declare(strict_types=1);

namespace Support\KnowledgeBase\Infrastructure\Doctrine\ORM;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Support\KnowledgeBase\Domain\Article\Article;
use Support\KnowledgeBase\Domain\Category\Category;
use Support\KnowledgeBase\Domain\Category\CategoryCollection;
use Support\KnowledgeBase\Domain\Category\CategoryLocale;
use Support\KnowledgeBase\Domain\Category\CategoryOverviewCollection;
use Support\KnowledgeBase\Domain\Category\CategoryOverviewItem;
use Support\KnowledgeBase\Domain\Category\CategoryRepository as BaseCategoryRepository;
use Support\KnowledgeBase\Domain\Category\CategorySlug;
use Support\System\Domain\I18n\Locale;
use Support\System\Domain\I18n\UsedLocale;

final class CategoryRepository implements BaseCategoryRepository
{
    private readonly EntityRepository $entityRepository;

    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    ) {
        $this->entityRepository = $this->entityManager->getRepository(Category::class);
    }

    public function findCategoryBySlug(
        CategoryLocale $locale,
        CategorySlug $slug,
    ): ?Category {
        $qb = $this->entityManager->createQueryBuilder();
        $qb->select('c');
        $qb->from(Category::class, 'c');
        $qb->join('c.lastRevision', 'r');
        $qb->join('r.locale', 'l');
        $qb->where($qb->expr()->eq('r.slug', ':slug'));
        $qb->andWhere($qb->expr()->eq('l.slug', ':locale'));
        $qb->setParameter('locale', $locale->value());
        $qb->setParameter('slug', $slug->value());
        $qb->setMaxResults(1);

        return $qb->getQuery()->getOneOrNullResult();
    }

    public function queryCategoryOverview(UsedLocale $locale): CategoryOverviewCollection
    {
        $qb = $this->entityManager->createQueryBuilder();
        $qb->select('c');
        $qb->from(Category::class, 'c');
        $qb->join('c.lastRevision', 'r');
        $qb->where($qb->expr()->eq('r.locale', ':locale'));
        $qb->orderBy($qb->expr()->asc('r.name'));
        $qb->setParameter('locale', $locale->getId()->toString());

        $categories = $qb->getQuery()->getResult();

        $qb = $this->entityManager->createQueryBuilder();
        $qb->select([
            'COUNT(a.id) AS articleCount',
            'c.id AS categoryId',
        ]);
        $qb->from(Article::class, 'a');
        $qb->join('a.lastRevision', 'r');
        $qb->join('r.categories', 'c');
        $qb->where($qb->expr()->in('c.id', ':categories'));
        $qb->addGroupBy('c.id');
        $qb->setParameter('categories', array_map(static function (Category $category): string {
            return $category->getId()->toString();
        }, $categories));

        $articleCounts = $qb->getQuery()->getResult();

        $result = new CategoryOverviewCollection();

        foreach ($categories as $category) {
            $count = 0;
            foreach ($articleCounts as $articleCount) {
                if ($articleCount['categoryId']->equals($category->getId())) {
                    $count = $articleCount['articleCount'];
                    break;
                }
            }

            $result->add(new CategoryOverviewItem(
                $category,
                $count,
            ));
        }

        return $result;
    }

    public function save(Category $category): void
    {
        $this->entityManager->persist($category);
        $this->entityManager->flush();
    }
}
