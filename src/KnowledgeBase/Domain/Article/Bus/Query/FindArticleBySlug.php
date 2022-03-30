<?php

declare(strict_types=1);

namespace Support\KnowledgeBase\Domain\Article\Bus\Query;

final class FindArticleBySlug
{
    public function __construct(
        public readonly string $slug,
    ) {
    }
}
