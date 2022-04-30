<?php

declare(strict_types=1);

namespace Support\KnowledgeBase\Domain\Category;

use DateTimeImmutable;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Support\Admin\Domain\Account\User;
use Support\KnowledgeBase\Domain\Media\File;
use Support\System\Domain\I18n\UsedLocale;

class CategoryRevision
{
    private UuidInterface $id;
    private DateTimeImmutable $createdAt;
    private User $createdBy;
    private Category $category;
    private ?UsedLocale $locale;
    private string $name;
    private string $slug;
    private ?File $thumbnail;

    public function __construct(
        User $createdBy,
        Category $category,
        ?UsedLocale $locale,
        string $name,
        string $slug,
    ) {
        $this->id = Uuid::uuid4();
        $this->createdAt = new DateTimeImmutable();
        $this->createdBy = $createdBy;
        $this->category = $category;
        $this->locale = $locale;
        $this->name = $name;
        $this->slug = $slug;
        $this->thumbnail = null;
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getCreatedBy(): User
    {
        return $this->createdBy;
    }

    public function getCategory(): Category
    {
        return $this->category;
    }

    public function getLocale(): ?UsedLocale
    {
        return $this->locale;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function getThumbnail(): ?File
    {
        return $this->thumbnail;
    }

    public function setThumbnail(?File $thumbnail): void
    {
        $this->thumbnail = $thumbnail;
    }
}
