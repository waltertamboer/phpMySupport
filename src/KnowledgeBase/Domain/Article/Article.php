<?php

declare(strict_types=1);

namespace Support\KnowledgeBase\Domain\Article;

use DateTimeImmutable;
use Doctrine\Common\Collections\Collection;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Support\Admin\Domain\Account\User;
use Support\System\Domain\I18n\UsedLocale;

class Article
{
    private UuidInterface $id;
    private DateTimeImmutable $createdAt;
    private Collection $revisions;
    private ArticleRevision $lastRevision;

    public function __construct(
        private readonly User $createdBy,
        UsedLocale $locale,
        string $title,
        string $slug,
        string $body,
    ) {
        $this->id = Uuid::uuid4();
        $this->createdAt = new DateTimeImmutable();
        $this->revisions = new ArticleRevisionCollection();

        $this->createRevision($this->createdBy, $locale, $title, $slug, $body);
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function createRevision(
        User $user,
        UsedLocale $locale,
        string $title,
        string $slug,
        string $body
    ): ArticleRevision {
        $this->lastRevision = new ArticleRevision($this, $user, $locale, $title, $slug, $body);

        $this->revisions->add($this->lastRevision);

        return $this->lastRevision;
    }

    /**
     * @return ArticleRevision[]
     */
    public function getRevisions(): array
    {
        return $this->revisions->toArray();
    }

    public function getLastRevision(): ArticleRevision
    {
        return $this->lastRevision;
    }
}
