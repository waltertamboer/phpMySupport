<?php

declare(strict_types=1);

namespace Support\KnowledgeBase\Application\RequestHandler;

use Doctrine\ORM\EntityManagerInterface;
use Laminas\Diactoros\Response\HtmlResponse;
use Mezzio\Template\TemplateRendererInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Support\System\Application\Middleware\LocalizationMiddleware;

final class Search implements RequestHandlerInterface
{
    public function __construct(
        private readonly TemplateRendererInterface $renderer,
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $usedLocale = $request->getAttribute(LocalizationMiddleware::USED_LOCALIZATION_ATTRIBUTE);

        $page = (int)($request->getQueryParams()['page'] ?? 1);
        $pageSize = 25;
        $query = $request->getQueryParams()['q'] ?? '';

        $articles = [];
        $noQueryError = true;

        if ($query !== '') {
            $noQueryError = false;
            $query = '%' . $query . '%';

            $qb = $this->entityManager->createQueryBuilder();
            $qb->select('a');
            $qb->from(\Support\KnowledgeBase\Domain\Article\Article::class, 'a');
            $qb->join('a.lastRevision', 'r');
            $qb->where($qb->expr()->eq('IDENTITY(r.locale)', ':locale'));
            $qb->andWhere(
                $qb->expr()->orX(
                    $qb->expr()->like('LOWER(r.title)', ':query'),
                    $qb->expr()->like('LOWER(r.body)', ':query')
                )
            );
            $qb->setParameter('query', strtolower($query));
            $qb->setParameter('locale', $usedLocale->getId()->toString());
            $qb->setMaxResults($pageSize);
            $qb->setFirstResult($page * $pageSize - $pageSize);

            $articles = $qb->getQuery()->getResult();
        }

        $response = new HtmlResponse($this->renderer->render(
            '@site/knowledge-base/search.html.twig',
            [
                'locale' => $usedLocale->getSlug(),
                'usedLocale' => $usedLocale,
                'articles' => $articles,
                'noQueryError' => $noQueryError,
            ],
        ));

        return $response;
    }
}
