<?php

declare(strict_types=1);

namespace Support\KnowledgeBase\Domain\Ticket;

use DateTimeImmutable;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class TicketAttachment
{
    private UuidInterface $id;
    private DateTimeImmutable $createdAt;
    private string $name;
    private string $mimeType;
    private int $size;

    public function __construct(string $name, string $mimeType, int $size)
    {
        $this->id = Uuid::uuid4();
        $this->createdAt = new DateTimeImmutable();
        $this->name = $name;
        $this->mimeType = $mimeType;
        $this->size = $size;
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getMimeType(): string
    {
        return $this->mimeType;
    }

    public function getSize(): int
    {
        return $this->size;
    }
}
