<?php

declare(strict_types=1);

namespace Support\KnowledgeBase\Domain\Category\Bus\Query;

use Support\KnowledgeBase\Domain\Category\CategorySlug;

final class FindCategoryBySlug
{
    public function __construct(
        public readonly CategorySlug $slug,
    ) {
    }
}
