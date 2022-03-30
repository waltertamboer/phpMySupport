<?php

declare(strict_types=1);

namespace Support\Admin\Application\RequestHandler\User;

use Doctrine\ORM\EntityManagerInterface;
use Laminas\Diactoros\Response\HtmlResponse;
use Laminas\Diactoros\Response\RedirectResponse;
use Mezzio\Authentication\UserInterface;
use Mezzio\Template\TemplateRendererInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Support\Admin\Domain\Account\User;
use Support\KnowledgeBase\Domain\Category\Category;
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
        $currentUser = $request->getAttribute(UserInterface::class);
        if ($currentUser === null) {
            return new RedirectResponse('/admin/login');
        }

        $entityId = $request->getAttribute('id');
        $entity = $this->entityManager->find(User::class, $entityId);

        if ($entity === null || $entity === $currentUser) {
            throw ResourceNotFound::fromRequest($request);
        }

        if ($request->getMethod() === 'POST') {
            $this->entityManager->remove($entity);
            $this->entityManager->flush();

            return new RedirectResponse('/admin/users');
        }

        return new HtmlResponse($this->renderer->render(
            '@admin/user/delete.html.twig',
            [
                'entity' => $entity,
            ],
        ));
    }
}
