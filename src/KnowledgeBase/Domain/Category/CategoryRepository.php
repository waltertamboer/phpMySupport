<?php

declare(strict_types=1);

namespace Support\KnowledgeBase\Domain\Category;

interface CategoryRepository
{
    public function findCategoryBySlug(CategorySlug $slug): ?Category;
    public function queryCategoryOverview(): CategoryOverviewCollection;
    public function save(Category $category): void;

}
