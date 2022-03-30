<?php

declare(strict_types=1);

namespace Support\KnowledgeBase\Domain\Category\Bus\Command;

use Psr\Http\Message\ServerRequestInterface;
use Support\KnowledgeBase\Domain\Category\Category;
use Support\System\Domain\Bus\Command\Command;

final class RegisterCategoryView implements Command
{
    public function __construct(
        public readonly Category $category,
        public readonly ServerRequestInterface $request,
    ) {
    }
}
