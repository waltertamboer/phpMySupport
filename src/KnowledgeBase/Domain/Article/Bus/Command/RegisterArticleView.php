<?php

declare(strict_types=1);

namespace Support\KnowledgeBase\Domain\Article\Bus\Command;

use Psr\Http\Message\ServerRequestInterface;
use Support\KnowledgeBase\Domain\Article\Article;
use Support\System\Domain\Bus\Command\Command;

final class RegisterArticleView implements Command
{
    public function __construct(
        public readonly Article $article,
        public readonly ServerRequestInterface $request,
    ) {
    }
}
