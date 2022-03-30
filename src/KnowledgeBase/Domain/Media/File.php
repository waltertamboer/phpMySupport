<?php

declare(strict_types=1);

namespace Support\KnowledgeBase\Domain\Media;

use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Support\Admin\Domain\Account\User;

class File
{
    private UuidInterface $id;
    private DateTimeImmutable $createdAt;
    private User $createdBy;
    private Collection $revisions;
    private FileRevision $lastRevision;

    public function __construct(User $user, string $name, string $mimeType, int $size)
    {
        $this->id = Uuid::uuid4();
        $this->createdAt = new DateTimeImmutable();
        $this->createdBy = $user;
        $this->revisions = new ArrayCollection();

        $this->createRevision($user, $name, $mimeType, $size);
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

    public function getRevisions(): array
    {
        return $this->revisions->toArray();
    }

    public function getLastRevision(): FileRevision
    {
        return $this->lastRevision;
    }

    public function createRevision(User $user, string $name, string $mimeType, int $size): FileRevision
    {
        $this->lastRevision = new FileRevision($user, $this, $name, $mimeType, $size);

        $this->revisions->add($this->lastRevision);

        return $this->lastRevision;
    }
}
