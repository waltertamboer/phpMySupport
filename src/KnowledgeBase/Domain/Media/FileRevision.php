<?php

declare(strict_types=1);

namespace Support\KnowledgeBase\Domain\Media;

use DateTimeImmutable;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use RuntimeException;
use Support\Admin\Domain\Account\User;

class FileRevision
{
    private UuidInterface $id;
    private DateTimeImmutable $createdAt;
    private User $createdBy;
    private File $file;
    private string $name;
    private string $mimeType;
    private int $size;

    public function __construct(User $user, File $file, string $name, string $mimeType, int $size)
    {
        $this->id = Uuid::uuid4();
        $this->createdAt = new DateTimeImmutable();
        $this->createdBy = $user;
        $this->file = $file;
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

    public function getCreatedBy(): User
    {
        return $this->createdBy;
    }

    public function getFile(): File
    {
        return $this->file;
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

    public function getTargetPath(): string
    {
        return 'data/media/' . $this->getId()->toString();
    }

    public function copyToPath(string $cachedPath): void
    {
        $rs = fopen($this->getTargetPath(), 'rb');
        if ($rs === false) {
            throw new RuntimeException('Failed to open read stream to ' . $this->getTargetPath());
        }

        $ws = fopen($cachedPath, 'wb');
        if ($ws === false) {
            throw new RuntimeException('Failed to open write stream to ' . $cachedPath);
        }

        stream_copy_to_stream($rs, $ws);

        fclose($rs);
        fclose($ws);
    }
}
