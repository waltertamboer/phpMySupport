<?php

declare(strict_types=1);

namespace Support\KnowledgeBase\Domain\Category;

final class CategoryOverviewItem
{
    public function __construct(
        private readonly Category $category,
        private readonly int $articleCount,
    )
    {
    }

    public function getCategory(): Category
    {
        return $this->category;
    }

    public function getArticleCount(): int
    {
        return $this->articleCount;
    }
}
