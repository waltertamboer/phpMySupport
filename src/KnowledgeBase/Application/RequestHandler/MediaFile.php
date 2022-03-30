<?php

declare(strict_types=1);

namespace Support\KnowledgeBase\Application\RequestHandler;

use Doctrine\ORM\EntityManagerInterface;
use Laminas\Diactoros\Response;
use Laminas\Diactoros\Response\JsonResponse;
use Mezzio\Template\TemplateRendererInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Ramsey\Uuid\Uuid;
use Support\KnowledgeBase\Domain\Media\File;
use Support\System\Application\Exception\ResourceNotFound;

final class MediaFile implements RequestHandlerInterface
{
    public function __construct(
        private readonly TemplateRendererInterface $renderer,
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $mediaFileId = $request->getAttribute('id');

        if (!is_string($mediaFileId) || !Uuid::isValid($mediaFileId)) {
            throw ResourceNotFound::fromRequest($request);
        }

        $mediaFile = $this->entityManager->find(File::class, $mediaFileId);

        if ($mediaFile === null) {
            throw ResourceNotFound::fromRequest($request);
        }

        $cachedPath = 'public/files/' . $mediaFile->getLastRevision()->getId()->toString();
        if (!is_file($cachedPath)) {
            $targetPath = $mediaFile->getLastRevision()->getTargetPath();
            if (!is_file($targetPath)) {
                throw ResourceNotFound::fromRequest($request);
            }

            $mediaFile->getLastRevision()->copyToPath($cachedPath);
        }

        return new Response(fopen($cachedPath, 'rb'), 200, [
            'Content-Type' => $mediaFile->getLastRevision()->getMimeType(),
        ]);
    }
}
