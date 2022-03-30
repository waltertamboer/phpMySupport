<?php

declare(strict_types=1);

namespace Support\Admin\Domain;

use Ramsey\Uuid\UuidInterface;

final class PopulairCategory
{
    public function __construct(
        private readonly UuidInterface $categoryId,
        private readonly string $categoryName,
        private readonly string $categorySlug,
        private readonly int $views,
    ) {
    }

    public function getCategoryId(): UuidInterface
    {
        return $this->categoryId;
    }

    public function getCategoryName(): string
    {
        return $this->categoryName;
    }

    public function getCategorySlug(): string
    {
        return $this->categorySlug;
    }

    public function getViews(): int
    {
        return $this->views;
    }
}
