<?php

declare(strict_types=1);

namespace Support\KnowledgeBase\Domain\Category;

use Support\System\Domain\I18n\UsedLocale;

interface CategoryRepository
{
    public function findCategoryBySlug(
        CategoryLocale $locale,
        CategorySlug $slug,
    ): ?Category;

    public function queryCategoryOverview(UsedLocale $locale): CategoryOverviewCollection;
    public function save(Category $category): void;

}
