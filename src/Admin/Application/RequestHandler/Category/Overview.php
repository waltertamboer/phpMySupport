<?php

declare(strict_types=1);

namespace Support\Admin\Application\RequestHandler\Category;

use Doctrine\ORM\EntityManagerInterface;
use Laminas\Diactoros\Response\HtmlResponse;
use Mezzio\Authentication\UserInterface;
use Mezzio\Template\TemplateRendererInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Support\KnowledgeBase\Domain\Article\Article;
use Support\KnowledgeBase\Domain\Category\Category;
use Support\System\Application\Exception\ResourceNotFound;
use Support\System\Domain\I18n\LocaleRepository;
use Support\System\Domain\Value\AnsiString;

final class Overview implements RequestHandlerInterface
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
        $qb->select('c');
        $qb->from(Category::class, 'c');
        $qb->join('c.lastRevision', 'lr');

        $sortQuery = ($request->getQueryParams()['sort']) ?? '+name';
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

            case '-name':
                $qb->orderBy($qb->expr()->desc('lr.name'));
                break;

            default:
                $qb->orderBy($qb->expr()->asc('lr.name'));
                break;
        }

        $categories = $qb->getQuery()->getResult();

        return new HtmlResponse($this->renderer->render(
            '@admin/category/overview.html.twig',
            [
                'sortQuery' => $sortQuery,
                'categories' => array_map(function (Category $category): array {
                    return [
                        'id' => $category->getId()->toString(),
                        'lastRevision' => [
                            'id' => $category->getLastRevision()->getId(),
                            'name' => $category->getLastRevision()->getName(),
                            'slug' => $category->getLastRevision()->getSlug(),
                            'locale' => $this->localeRepository->lookup($category->getLastRevision()->getLocale()),
                        ],
                    ];
                }, $categories),
            ],
        ));
    }
}
