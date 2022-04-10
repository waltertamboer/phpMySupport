<?php

declare(strict_types=1);

namespace Support\KnowledgeBase\Application\RequestHandler;

use Laminas\Diactoros\Response\HtmlResponse;
use Mezzio\Template\TemplateRendererInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Support\KnowledgeBase\Domain\Category\Bus\Command\RegisterCategoryView;
use Support\KnowledgeBase\Domain\Category\Bus\Query\FindArticlesForCategory;
use Support\KnowledgeBase\Domain\Category\Bus\Query\FindCategoryBySlug;
use Support\KnowledgeBase\Domain\Category\CategoryLocale;
use Support\KnowledgeBase\Domain\Category\CategorySlug;
use Support\System\Application\Exception\ResourceNotFound;
use Support\System\Domain\Bus\Command\CommandBus;
use Support\System\Domain\Bus\Query\QueryBus;

final class Category implements RequestHandlerInterface
{
    public function __construct(
        private readonly TemplateRendererInterface $renderer,
        private readonly CommandBus $commandBus,
        private readonly QueryBus $queryBus,
    ) {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $category = $this->queryBus->query(new FindCategoryBySlug(
            new CategoryLocale($request->getAttribute('locale')),
            new CategorySlug($request->getAttribute('slug')),
        ));
        assert($category === null || $category instanceof \Support\KnowledgeBase\Domain\Category\Category);

        if ($category === null) {
            throw ResourceNotFound::fromRequest($request);
        }

        $this->commandBus->dispatch(new RegisterCategoryView($category, $request));

        $articles = $this->queryBus->query(new FindArticlesForCategory($category));

        $response = new HtmlResponse($this->renderer->render(
            '@site/knowledge-base/category.html.twig',
            [
                'category' => $category,
                'articles' => $articles,
            ]
        ));

        return $response->withAddedHeader('Content-Language', $category->getLastRevision()->getLocale());
    }
}
