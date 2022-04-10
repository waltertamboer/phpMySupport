<?php

declare(strict_types=1);

namespace Support\KnowledgeBase\Domain\Category;

interface CategoryRepository
{
    public function findCategoryBySlug(
        CategoryLocale $locale,
        CategorySlug $slug,
    ): ?Category;

    public function queryCategoryOverview(string $locale): CategoryOverviewCollection;
    public function save(Category $category): void;

}
