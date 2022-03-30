<?php

declare(strict_types=1);

namespace Support\Admin\Application\RequestHandler\Article;

use Doctrine\ORM\EntityManagerInterface;
use Laminas\Diactoros\Response\HtmlResponse;
use Mezzio\Template\TemplateRendererInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Support\KnowledgeBase\Domain\Article\Article;

final class Overview implements RequestHandlerInterface
{
    public function __construct(
        private readonly TemplateRendererInterface $renderer,
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $qb = $this->entityManager->createQueryBuilder();
        $qb->select('a');
        $qb->from(Article::class, 'a');
        $qb->join('a.lastRevision', 'r');
        $qb->orderBy($qb->expr()->asc('r.title'));

        $articles = $qb->getQuery()->getResult();

        return new HtmlResponse($this->renderer->render(
            '@admin/article/overview.html.twig',
            [
                'articles' => $articles,
            ],
        ));
    }
}