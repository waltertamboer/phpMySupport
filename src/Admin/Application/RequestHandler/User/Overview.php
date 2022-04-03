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

final class Overview implements RequestHandlerInterface
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

        $qb = $this->entityManager->createQueryBuilder();
        $qb->select('u');
        $qb->from(User::class, 'u');
        $qb->orderBy($qb->expr()->asc('u.username'));

        $users = $qb->getQuery()->getResult();

        return new HtmlResponse($this->renderer->render(
            '@admin/user/overview.html.twig',
            [
                'users' => $users,
            ],
        ));
    }
}
