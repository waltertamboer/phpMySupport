<?php

declare(strict_types=1);

namespace Support\Admin\Application\RequestHandler\Article;

use Doctrine\ORM\EntityManagerInterface;
use Laminas\Diactoros\Response\HtmlResponse;
use Laminas\Diactoros\Response\RedirectResponse;
use Mezzio\Authentication\UserInterface;
use Mezzio\Template\TemplateRendererInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Support\KnowledgeBase\Domain\Article\Article;
use Support\System\Application\Exception\ResourceNotFound;

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
        if ($user === null) {
            return new RedirectResponse('/admin/login');
        }

        $entityId = $request->getAttribute('id');
        $entity = $this->entityManager->find(Article::class, $entityId);

        if ($entity === null) {
            throw ResourceNotFound::fromRequest($request);
        }

        if ($request->getMethod() === 'POST') {
            $this->entityManager->remove($entity);
            $this->entityManager->flush();

            return new RedirectResponse('/admin/articles');
        }

        return new HtmlResponse($this->renderer->render(
            '@admin/article/delete.html.twig',
            [
                'entity' => $entity,
            ],
        ));
    }
}
