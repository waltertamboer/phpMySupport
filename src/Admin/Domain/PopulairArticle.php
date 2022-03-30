<?php

declare(strict_types=1);

namespace Support\Admin\Domain;

use Ramsey\Uuid\UuidInterface;

final class PopulairArticle
{
    public function __construct(
        private readonly UuidInterface $articleId,
        private readonly string $articleTitle,
        private readonly string $articleSlug,
        private readonly int $views,
    ) {
    }

    public function getArticleId(): UuidInterface
    {
        return $this->articleId;
    }

    public function getArticleTitle(): string
    {
        return $this->articleTitle;
    }

    public function getArticleSlug(): string
    {
        return $this->articleSlug;
    }

    public function getViews(): int
    {
        return $this->views;
    }
}
