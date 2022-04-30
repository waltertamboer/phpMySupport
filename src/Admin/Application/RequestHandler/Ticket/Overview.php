<?php

declare(strict_types=1);

namespace Support\Admin\Application\RequestHandler\Ticket;

use Doctrine\ORM\EntityManagerInterface;
use Laminas\Diactoros\Response\HtmlResponse;
use Mezzio\Authentication\UserInterface;
use Mezzio\Template\TemplateRendererInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Support\KnowledgeBase\Domain\Article\Article;
use Support\KnowledgeBase\Domain\Category\Category;
use Support\KnowledgeBase\Domain\Ticket\Ticket;
use Support\System\Application\Exception\ResourceNotFound;
use Support\System\Domain\I18n\LocaleQueryRepository;
use Support\System\Domain\Value\AnsiString;

final class Overview implements RequestHandlerInterface
{
    public function __construct(
        private readonly TemplateRendererInterface $renderer,
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $user = $request->getAttribute(UserInterface::class);

        if ($user === null || !$user->isEditor()) {
            throw ResourceNotFound::fromRequest($request);
        }

        $sortQuery = ($request->getQueryParams()['sort']) ?? '+createdAt';
        switch ($sortQuery) {
            case '+createdAt':
                break;

            case '-createdAt':
                break;

            default:
                break;
        }

        $tickets = [];//$qb->getQuery()->getResult();

        return new HtmlResponse($this->renderer->render(
            '@admin/ticket/overview.html.twig',
            [
                'sortQuery' => $sortQuery,
                'tickets' => $tickets,
            ],
        ));
    }
}
