<?php

declare(strict_types=1);

namespace Support\Admin\Application\RequestHandler\Media;

use Doctrine\ORM\EntityManagerInterface;
use Laminas\Diactoros\Response\HtmlResponse;
use Laminas\Diactoros\Response\RedirectResponse;
use Mezzio\Authentication\UserInterface;
use Mezzio\Template\TemplateRendererInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;


use Support\KnowledgeBase\Domain\Article\Article;
use Support\KnowledgeBase\Domain\Category\Category;
use Support\KnowledgeBase\Domain\Media\File;
use Support\KnowledgeBase\Domain\Media\FileRevision;
use Support\System\Application\Exception\ResourceNotFound;
use Support\System\Domain\Value\AnsiString;

final class Delete implements RequestHandlerInterface
{
    public function __construct(
        private readonly TemplateRendererInterface $renderer,
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $user = $request->getAttribute(UserInterface::class);

        if ($user === null || !$user->isAdmin()) {
            throw ResourceNotFound::fromRequest($request);
        }

        $entityId = $request->getAttribute('id');
        $entity = $this->entityManager->find(File::class, $entityId);

        if ($entity === null) {
            throw ResourceNotFound::fromRequest($request);
        }

        if ($request->getMethod() === 'POST') {
            $paths = array_map(static function (FileRevision $revision): string {
                return $revision->getTargetPath();
            }, $entity->getRevisions());

            $this->entityManager->remove($entity);
            $this->entityManager->flush();

            foreach ($paths as $path) {
                if (is_file($path)) {
                    unlink($path);
                }
            }

            return new RedirectResponse('/admin/media');
        }

        return new HtmlResponse($this->renderer->render(
            '@admin/media/delete.html.twig',
            [
                'entity' => $entity,
            ],
        ));
    }
}
