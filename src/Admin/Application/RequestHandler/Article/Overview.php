<?php

declare(strict_types=1);

namespace Support\Admin\Application\RequestHandler\Article;

use Doctrine\ORM\EntityManagerInterface;
use Laminas\Diactoros\Response\HtmlResponse;
use Mezzio\Authentication\UserInterface;
use Mezzio\Template\TemplateRendererInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Support\KnowledgeBase\Domain\Article\Article;
use Support\System\Application\Exception\ResourceNotFound;
use Support\System\Domain\I18n\LocaleQueryRepository;

final class Overview implements RequestHandlerInterface
{
    public function __construct(
        private readonly TemplateRendererInterface $renderer,
        private readonly EntityManagerInterface $entityManager,
        private readonly LocaleQueryRepository $LocaleQueryRepository,
    ) {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $user = $request->getAttribute(UserInterface::class);

        if ($user === null || !$user->isEditor()) {
            throw ResourceNotFound::fromRequest($request);
        }

        $qb = $this->entityManager->createQueryBuilder();
        $qb->select('a');
        $qb->from(Article::class, 'a');
        $qb->join('a.lastRevision', 'lr');

        $sortQuery = ($request->getQueryParams()['sort']) ?? '+title';
        switch ($sortQuery) {
            case '+locale':
                $qb->orderBy($qb->expr()->asc('lr.locale'));
                break;

            case '-locale':
                $qb->orderBy($qb->expr()->desc('lr.locale'));
                break;

            case '+slug':
                $qb->orderBy($qb->expr()->asc('lr.slug'));
                break;

            case '-slug':
                $qb->orderBy($qb->expr()->desc('lr.slug'));
                break;

            case '-title':
                $qb->orderBy($qb->expr()->desc('lr.title'));
                break;

            default:
                $qb->orderBy($qb->expr()->asc('lr.title'));
                break;
        }

        $articles = $qb->getQuery()->getResult();

        return new HtmlResponse($this->renderer->render(
            '@admin/article/overview.html.twig',
            [
                'sortQuery' => $sortQuery,
                'articles' => array_map(function (Article $article): array {
                    return [
                        'id' => $article->getId()->toString(),
                        'lastRevision' => [
                            'id' => $article->getLastRevision()->getId()->toString(),
                            'title' => $article->getLastRevision()->getTitle(),
                            'slug' => $article->getLastRevision()->getSlug(),
                            'locale' => $article->getLastRevision()->getLocale(),
                        ],
                    ];
                }, $articles),
            ],
        ));
    }
}
