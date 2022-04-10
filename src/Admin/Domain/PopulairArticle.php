<?php

declare(strict_types=1);

namespace Support\Admin\Domain;

use Ramsey\Uuid\UuidInterface;
use Support\System\Domain\I18n\Locale;

final class PopulairArticle
{
    public function __construct(
        private readonly UuidInterface $articleId,
        private readonly string $articleTitle,
        private readonly string $articleSlug,
        private readonly Locale $articleLocale,
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

    public function getArticleLocale(): Locale
    {
        return $this->articleLocale;
    }

    public function getViews(): int
    {
        return $this->views;
    }
}
