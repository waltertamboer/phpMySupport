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
use Support\System\Application\Exception\ResourceNotFound;

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

        if ($currentUser === null || !$currentUser->isOwner()) {
            throw ResourceNotFound::fromRequest($request);
        }

        $qb = $this->entityManager->createQueryBuilder();
        $qb->select('u');
        $qb->from(User::class, 'u');

        $sortQuery = ($request->getQueryParams()['sort']) ?? '+title';
        switch ($sortQuery) {
            case '+role':
                $qb->orderBy($qb->expr()->asc('u.role'));
                break;

            case '-role':
                $qb->orderBy($qb->expr()->desc('u.role'));
                break;

            case '-username':
                $qb->orderBy($qb->expr()->desc('u.username'));
                break;

            default:
                $qb->orderBy($qb->expr()->asc('u.username'));
                break;
        }

        $users = $qb->getQuery()->getResult();

        return new HtmlResponse($this->renderer->render(
            '@admin/user/overview.html.twig',
            [
                'sortQuery' => $sortQuery,
                'users' => $users,
            ],
        ));
    }
}
