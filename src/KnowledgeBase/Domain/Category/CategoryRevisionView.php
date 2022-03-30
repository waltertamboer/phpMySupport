<?php

declare(strict_types=1);

namespace Support\KnowledgeBase\Domain\Category;

use DateTimeImmutable;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Support\System\Domain\Value\IPAddress;
use Support\System\Domain\Value\UserAgent;

class CategoryRevisionView
{
    private UuidInterface $id;
    private DateTimeImmutable $createdAt;
    private CategoryRevision $categoryRevision;
    private IPAddress $ipAddress;
    private UserAgent $userAgent;

    public function __construct(CategoryRevision $categoryRevision, IPAddress $ipAddress, UserAgent $userAgent)
    {
        $this->id = Uuid::uuid4();
        $this->createdAt = new DateTimeImmutable();
        $this->categoryRevision = $categoryRevision;
        $this->ipAddress = $ipAddress;
        $this->userAgent = $userAgent;
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getCategoryRevision(): CategoryRevision
    {
        return $this->categoryRevision;
    }

    public function getIpAddress(): IPAddress
    {
        return $this->ipAddress;
    }

    public function getUserAgent(): UserAgent
    {
        return $this->userAgent;
    }
}
