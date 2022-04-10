<?php

declare(strict_types=1);

namespace Support\KnowledgeBase\Domain\Category\Bus\Query;

use Support\KnowledgeBase\Domain\Category\Category;
use Support\KnowledgeBase\Domain\Category\CategoryCollection;
use Support\KnowledgeBase\Domain\Category\CategoryName;
use Support\KnowledgeBase\Domain\Category\CategoryRepository;
use Support\KnowledgeBase\Domain\Category\CategorySlug;

final class FindCategoryBySlugHandler
{
    public function __construct(
        private readonly CategoryRepository $repository,
    ) {
    }

    public function __invoke(FindCategoryBySlug $query): ?Category
    {
        return $this->repository->findCategoryBySlug($query->locale, $query->slug);
    }
}
