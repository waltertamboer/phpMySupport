<?php

declare(strict_types=1);

namespace Support\KnowledgeBase\Domain\Category\Bus\Query;

use Support\KnowledgeBase\Domain\Category\CategoryLocale;
use Support\KnowledgeBase\Domain\Category\CategorySlug;

final class FindCategoryBySlug
{
    public function __construct(
        public readonly CategoryLocale $locale,
        public readonly CategorySlug $slug,
    ) {
    }
}
