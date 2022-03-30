<?php

declare(strict_types=1);

namespace Support\Admin\Application\RequestHandler\Dashboard;

use Doctrine\ORM\EntityManagerInterface;
use Laminas\Diactoros\Response\HtmlResponse;
use Mezzio\Template\TemplateRendererInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Support\Admin\Domain\PopulairArticle;
use Support\Admin\Domain\PopulairCategory;

final class View implements RequestHandlerInterface
{
    public function __construct(
        private readonly TemplateRendererInterface $renderer,
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $qb = $this->entityManager->createQueryBuilder();
        $qb->select([
            'COUNT(arv.id) AS views',
            'a.id',
            'lr.title',
            'lr.slug',
        ]);
        $qb->from(\Support\KnowledgeBase\Domain\Article\ArticleRevisionView::class, 'arv');
        $qb->join('arv.articleRevision', 'ar');
        $qb->join('ar.article', 'a');
        $qb->join('a.lastRevision', 'lr');
        $qb->addGroupBy('a.id');
        $qb->addGroupBy('lr.title');
        $qb->addGroupBy('lr.slug');
        $qb->orderBy($qb->expr()->desc('views'));
        $qb->setMaxResults(10);

        $populairArticles = $qb->getQuery()->getResult();

        $qb = $this->entityManager->createQueryBuilder();
        $qb->select([
            'COUNT(crv.id) AS views',
            'c.id',
            'lr.name',
            'lr.slug',
        ]);
        $qb->from(\Support\KnowledgeBase\Domain\Category\CategoryRevisionView::class, 'crv');
        $qb->join('crv.categoryRevision', 'cr');
        $qb->join('cr.category', 'c');
        $qb->join('c.lastRevision', 'lr');
        $qb->addGroupBy('c.id');
        $qb->addGroupBy('lr.name');
        $qb->addGroupBy('lr.slug');
        $qb->orderBy($qb->expr()->desc('views'));
        $qb->setMaxResults(10);

        $populairCategories = $qb->getQuery()->getResult();

        return new HtmlResponse($this->renderer->render(
            '@admin/dashboard/view.html.twig',
            [
                'populairArticles' => array_map(static function (array $item): PopulairArticle {
                    return new PopulairArticle(
                        $item['id'],
                        $item['title'],
                        $item['slug'],
                        $item['views'],
                    );
                }, $populairArticles),
                'populairCategories' => array_map(static function (array $item): PopulairCategory {
                    return new PopulairCategory(
                        $item['id'],
                        $item['name'],
                        $item['slug'],
                        $item['views'],
                    );
                }, $populairCategories),
            ],
        ));
    }
}
