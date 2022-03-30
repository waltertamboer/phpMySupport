<?php

declare(strict_types=1);

namespace Support\KnowledgeBase\Domain\Article;

use DateTimeImmutable;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Support\System\Domain\Value\IPAddress;
use Support\System\Domain\Value\UserAgent;

class ArticleRevisionView
{
    private UuidInterface $id;
    private DateTimeImmutable $createdAt;
    private ArticleRevision $articleRevision;
    private IPAddress $ipAddress;
    private UserAgent $userAgent;

    public function __construct(ArticleRevision $articleRevision, IPAddress $ipAddress, UserAgent $userAgent)
    {
        $this->id = Uuid::uuid4();
        $this->createdAt = new DateTimeImmutable();
        $this->articleRevision = $articleRevision;
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

    public function getArticleRevision(): ArticleRevision
    {
        return $this->articleRevision;
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
