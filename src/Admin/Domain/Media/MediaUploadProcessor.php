<?php

declare(strict_types=1);

namespace Support\Admin\Domain\Media;

use Doctrine\ORM\EntityManagerInterface;
use Psr\Http\Message\UploadedFileInterface;
use Support\Admin\Domain\Account\User;
use Support\KnowledgeBase\Domain\Media\File;

final class MediaUploadProcessor
{
    private readonly EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function __invoke(User $user, UploadedFileInterface $uploadedFile): File
    {
        $file = new File(
            $user,
            $uploadedFile->getClientFilename(),
            $uploadedFile->getClientMediaType(),
            $uploadedFile->getSize()
        );

        $uploadedFile->moveTo($file->getLastRevision()->getTargetPath());

        $this->entityManager->persist($file);
        $this->entityManager->flush();

        return $file;
    }
}
