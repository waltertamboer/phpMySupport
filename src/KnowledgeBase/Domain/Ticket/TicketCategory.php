<?php

declare(strict_types=1);

namespace Support\KnowledgeBase\Domain\Ticket;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class TicketCategory
{
    private UuidInterface $id;
    private int $position;
    private string $name;

    public function __construct(string $name)
    {
        $this->id = Uuid::uuid4();
        $this->position = 0;
        $this->name = $name;
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function getPosition(): int
    {
        return $this->position;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
