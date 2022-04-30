<?php

declare(strict_types=1);

namespace Support\System\Domain\I18n;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Support\KnowledgeBase\Domain\Media\File;

class UsedLocale
{
    private UuidInterface $id;
    private ?string $selectorText;
    private ?File $thumbnail;

    public function __construct(
        private string $name,
        private string $slug,
        private bool $enabled,
    ) {
        $this->id = Uuid::uuid4();
        $this->selectorText = null;
        $this->thumbnail = null;
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): void
    {
        $this->slug = $slug;
    }

    public function getSelectorText(): ?string
    {
        return $this->selectorText;
    }

    public function setSelectorText(?string $selectorText): void
    {
        $this->selectorText = $selectorText;
    }

    public function getThumbnail(): ?File
    {
        return $this->thumbnail;
    }

    public function setThumbnail(?File $thumbnail): void
    {
        $this->thumbnail = $thumbnail;
    }

    public function getEnabled(): bool
    {
        return $this->enabled;
    }

    public function setEnabled(bool $enabled): void
    {
        $this->enabled = $enabled;
    }

    public function getCountry(): string
    {
        $splitted = explode('_', $this->getSlug());

        return strtolower($splitted[1] ?? $splitted[0]);
    }
}
