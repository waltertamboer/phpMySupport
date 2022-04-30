<?php

declare(strict_types=1);

namespace Support\KnowledgeBase\Domain\Ticket;

use DateTimeImmutable;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class Ticket
{
    private UuidInterface $id;
    private DateTimeImmutable $createdAt;
    private TicketReporter $reporter;
    private ?TicketCategory $category;
    private string $subject;
    private string $message;

    public function __construct(TicketReporter $reporter, string $subject, string $message)
    {
        $this->id = Uuid::uuid4();
        $this->createdAt = new DateTimeImmutable();
        $this->reporter = $reporter;
        $this->category = null;
        $this->subject = $subject;
        $this->message = $message;
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getReporter(): TicketReporter
    {
        return $this->reporter;
    }

    public function getCategory(): ?TicketCategory
    {
        return $this->category;
    }

    public function setCategory(?TicketCategory $category): void
    {
        $this->category = $category;
    }

    public function getSubject(): string
    {
        return $this->subject;
    }

    public function setSubject(string $subject): void
    {
        $this->subject = $subject;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function setMessage(string $message): void
    {
        $this->message = $message;
    }
}
