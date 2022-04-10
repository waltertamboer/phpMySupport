<?php

declare(strict_types=1);

namespace Support\KnowledgeBase\Domain\Category\Bus\Query;

use Support\KnowledgeBase\Domain\Category\CategoryOverviewCollection;
use Support\KnowledgeBase\Domain\Category\CategoryRepository;

final class GetCategoryOverviewHandler
{
    public function __construct(
        private readonly CategoryRepository $repository,
    ) {
    }

    public function __invoke(GetCategoryOverview $query): CategoryOverviewCollection
    {
        return $this->repository->queryCategoryOverview($query->getLocale());
    }
}
