<?php

declare(strict_types=1);

namespace Support\KnowledgeBase\Domain\Category\Bus\Query;

use Support\KnowledgeBase\Domain\Category\Category;

final class FindArticlesForCategory
{
    public function __construct(
        public readonly Category $category,
    ) {
    }
}
