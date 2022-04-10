<?php

declare(strict_types=1);

namespace Support\Admin\Application\RequestHandler\Dashboard;

use Doctrine\ORM\EntityManagerInterface;
use Laminas\Diactoros\Response\HtmlResponse;
use Mezzio\Authentication\UserInterface;
use Mezzio\Template\TemplateRendererInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Support\Admin\Domain\PopulairArticle;
use Support\Admin\Domain\PopulairCategory;
use Support\System\Application\Exception\ResourceNotFound;
use Support\System\Domain\I18n\LocaleRepository;

final class View implements RequestHandlerInterface
{
    public function __construct(
        private readonly TemplateRendererInterface $renderer,
        private readonly EntityManagerInterface $entityManager,
        private readonly LocaleRepository $localeRepository,
    ) {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $user = $request->getAttribute(UserInterface::class);

        if ($user === null || !$user->isEditor()) {
            throw ResourceNotFound::fromRequest($request);
        }

        $qb = $this->entityManager->createQueryBuilder();
        $qb->select([
            'COUNT(arv.id) AS views',
            'a.id',
            'lr.title',
            'lr.slug',
            'lr.locale',
        ]);
        $qb->from(\Support\KnowledgeBase\Domain\Article\ArticleRevisionView::class, 'arv');
        $qb->join('arv.articleRevision', 'ar');
        $qb->join('ar.article', 'a');
        $qb->join('a.lastRevision', 'lr');
        $qb->addGroupBy('a.id');
        $qb->addGroupBy('lr.title');
        $qb->addGroupBy('lr.slug');
        $qb->addGroupBy('lr.locale');
        $qb->setMaxResults(10);

        $sortQueryArticle = ($request->getQueryParams()['articleSort']) ?? '-views';
        switch ($sortQueryArticle) {
            case '+title':
                $qb->orderBy($qb->expr()->asc('lr.title'));
                break;

            case '-title':
                $qb->orderBy($qb->expr()->desc('lr.title'));
                break;

            case '+locale':
                $qb->orderBy($qb->expr()->asc('lr.locale'));
                break;

            case '-locale':
                $qb->orderBy($qb->expr()->desc('lr.locale'));
                break;

            case '+views':
                $qb->orderBy($qb->expr()->asc('views'));
                break;

            default:
                $qb->orderBy($qb->expr()->desc('views'));
                break;
        }

        $populairArticles = $qb->getQuery()->getResult();

        $qb = $this->entityManager->createQueryBuilder();
        $qb->select([
            'COUNT(crv.id) AS views',
            'c.id',
            'lr.name',
            'lr.slug',
            'lr.locale',
        ]);
        $qb->from(\Support\KnowledgeBase\Domain\Category\CategoryRevisionView::class, 'crv');
        $qb->join('crv.categoryRevision', 'cr');
        $qb->join('cr.category', 'c');
        $qb->join('c.lastRevision', 'lr');
        $qb->addGroupBy('c.id');
        $qb->addGroupBy('lr.name');
        $qb->addGroupBy('lr.slug');
        $qb->addGroupBy('lr.locale');
        $qb->setMaxResults(10);

        $sortQueryCategory = ($request->getQueryParams()['categorySort']) ?? '-views';
        switch ($sortQueryCategory) {
            case '+name':
                $qb->orderBy($qb->expr()->asc('lr.name'));
                break;

            case '-name':
                $qb->orderBy($qb->expr()->desc('lr.name'));
                break;

            case '+locale':
                $qb->orderBy($qb->expr()->asc('lr.locale'));
                break;

            case '-locale':
                $qb->orderBy($qb->expr()->desc('lr.locale'));
                break;

            case '+views':
                $qb->orderBy($qb->expr()->asc('views'));
                break;

            default:
                $qb->orderBy($qb->expr()->desc('views'));
                break;
        }

        $populairCategories = $qb->getQuery()->getResult();

        return new HtmlResponse($this->renderer->render(
            '@admin/dashboard/view.html.twig',
            [
                'sortArticle' => $sortQueryArticle,
                'sortCategory' => $sortQueryCategory,
                'populairArticles' => array_map(function (array $item): PopulairArticle {
                    return new PopulairArticle(
                        $item['id'],
                        $item['title'],
                        $item['slug'],
                        $this->localeRepository->lookup($item['locale']),
                        $item['views'],
                    );
                }, $populairArticles),
                'populairCategories' => array_map(function (array $item): PopulairCategory {
                    return new PopulairCategory(
                        $item['id'],
                        $item['name'],
                        $item['slug'],
                        $this->localeRepository->lookup($item['locale']),
                        $item['views'],
                    );
                }, $populairCategories),
            ],
        ));
    }
}
