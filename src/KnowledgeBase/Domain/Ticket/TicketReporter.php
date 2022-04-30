<?php

declare(strict_types=1);

namespace Support\KnowledgeBase\Domain\Ticket;

use DateTimeImmutable;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class TicketReporter
{
    private UuidInterface $id;
    private DateTimeImmutable $createdAt;
    private string $emailAddress;
    private string $password;

    public function __construct(string $emailAddress, string $password)
    {
        $this->id = Uuid::uuid4();
        $this->createdAt = new DateTimeImmutable();
        $this->emailAddress = $emailAddress;
        $this->password = $password;
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getEmailAddress(): string
    {
        return $this->emailAddress;
    }

    public function getPassword(): string
    {
        return $this->password;
    }
}
