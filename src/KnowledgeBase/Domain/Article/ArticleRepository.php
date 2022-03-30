<?php

declare(strict_types=1);

namespace Support\KnowledgeBase\Domain\Article;

use Support\System\Domain\Criteria\Criteria;

interface ArticleRepository
{
    public function query(Criteria $criteria): ArticleCollection;

    public function save(Article $article): void;
}
