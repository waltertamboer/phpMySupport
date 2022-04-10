<?php

declare(strict_types=1);

namespace Support\KnowledgeBase\Domain\Category;

use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Support\Admin\Domain\Account\User;

class Category
{
    private UuidInterface $id;
    private DateTimeImmutable $createdAt;
    private User $createdBy;
    private Collection $revisions;
    private CategoryRevision $lastRevision;
    private Collection $articles;

    public function __construct(
        User $createdBy,
        string $locale,
        string $name,
        string $slug,
    ) {
        $this->id = Uuid::uuid4();
        $this->createdAt = new DateTimeImmutable();
        $this->createdBy = $createdBy;
        $this->revisions = new ArrayCollection();
        $this->articles = new ArrayCollection();

        $this->createRevision($createdBy, $locale, $name, $slug);
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

    public function createRevision(User $createdBy, string $locale, string $name, string $slug): CategoryRevision
    {
        $this->lastRevision = new CategoryRevision($createdBy, $this, $locale, $name, $slug);

        $this->revisions->add($this->lastRevision);

        return $this->lastRevision;
    }

    public function getRevisions(): array
    {
        return $this->revisions->toArray();
    }

    public function getLastRevision(): CategoryRevision
    {
        return $this->lastRevision;
    }

    public function getArticles(): array
    {
        return $this->articles->toArray();
    }
}
