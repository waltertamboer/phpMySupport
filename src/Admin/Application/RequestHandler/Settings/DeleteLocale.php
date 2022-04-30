<?php

declare(strict_types=1);

namespace Support\Admin\Application\RequestHandler\Settings;

use Doctrine\ORM\EntityManagerInterface;
use Laminas\Diactoros\Response\HtmlResponse;
use Laminas\Diactoros\Response\RedirectResponse;
use Mezzio\Authentication\UserInterface;
use Mezzio\Template\TemplateRendererInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Support\KnowledgeBase\Domain\Category\Category;
use Support\KnowledgeBase\Domain\Media\File;
use Support\System\Application\Exception\ResourceNotFound;
use Support\System\Domain\I18n\UsedLocale;

final class DeleteLocale implements RequestHandlerInterface
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
        $entity = $this->entityManager->find(UsedLocale::class, $entityId);

        if ($entity === null) {
            throw ResourceNotFound::fromRequest($request);
        }

        if ($request->getMethod() === 'POST') {
            $this->entityManager->remove($entity);
            $this->entityManager->flush();

            return new RedirectResponse('/admin/settings/locales');
        }

        return new HtmlResponse($this->renderer->render(
            '@admin/settings/locales/delete.html.twig',
            [
                'entity' => $entity,
            ],
        ));
    }
}
