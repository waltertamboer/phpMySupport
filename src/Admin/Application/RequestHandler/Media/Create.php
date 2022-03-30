<?php

declare(strict_types=1);

namespace Support\Admin\Application\RequestHandler\Media;

use Doctrine\ORM\EntityManagerInterface;
use Laminas\Diactoros\Response\HtmlResponse;
use Laminas\Diactoros\Response\RedirectResponse;
use Laminas\Diactoros\UploadedFile;
use Mezzio\Authentication\UserInterface;
use Mezzio\Template\TemplateRendererInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Support\KnowledgeBase\Domain\Article\Article;
use Support\KnowledgeBase\Domain\Category\Category;
use Support\KnowledgeBase\Domain\Category\CategoryName;
use Support\KnowledgeBase\Domain\Category\CategorySlug;
use Support\KnowledgeBase\Domain\Media\File;

final class Create implements RequestHandlerInterface
{
    public function __construct(
        private readonly TemplateRendererInterface $renderer,
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $user = $request->getAttribute(UserInterface::class);
        if ($user === null) {
            return new RedirectResponse('/admin/login');
        }

        if ($request->getMethod() === 'POST') {
            $formData = $request->getUploadedFiles();

            $uploadedFile = $formData['file'] ?? null;
            assert($uploadedFile === null || $uploadedFile instanceof UploadedFile);

            if ($uploadedFile !== null) {
                $file = new File(
                    $user,
                    $uploadedFile->getClientFilename(),
                    $uploadedFile->getClientMediaType(),
                    $uploadedFile->getSize()
                );

                $uploadedFile->moveTo($file->getLastRevision()->getTargetPath());

                $this->entityManager->persist($file);
                $this->entityManager->flush();
            }

            return new RedirectResponse('/admin/media');
        }

        return new HtmlResponse($this->renderer->render(
            '@admin/media/create.html.twig',
            [
            ],
        ));
    }
}
