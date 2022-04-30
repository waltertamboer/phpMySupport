<?php

declare(strict_types=1);

namespace Support\KnowledgeBase\Application\RequestHandler;

use Laminas\Diactoros\Response\HtmlResponse;
use Mezzio\Template\TemplateRendererInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Support\KnowledgeBase\Domain\Article\Article as DomainArticle;
use Support\KnowledgeBase\Domain\Article\Bus\Command\RegisterArticleView;
use Support\KnowledgeBase\Domain\Article\Bus\Query\FindArticleBySlug;
use Support\System\Application\Exception\ResourceNotFound;
use Support\System\Domain\Bus\Command\CommandBus;
use Support\System\Domain\Bus\Query\QueryBus;
use Support\System\Domain\I18n\Bus\Query\GetUsedLocaleBySlug;
use Support\System\Domain\I18n\UsedLocale;

final class Article implements RequestHandlerInterface
{
    public function __construct(
        private readonly TemplateRendererInterface $renderer,
        private readonly CommandBus $commandBus,
        private readonly QueryBus $queryBus,
    ) {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $routeLocale = $request->getAttribute('locale');
        $routeSlug = $request->getAttribute('slug');

        $locale = $this->queryBus->query(new GetUsedLocaleBySlug($routeLocale));
        assert($locale === null || $locale instanceof UsedLocale);

        $article = $this->queryBus->query(new FindArticleBySlug($routeSlug));
        assert($article === null || $article instanceof DomainArticle);

        if ($article === null) {
            throw ResourceNotFound::fromRequest($request);
        }

        $this->commandBus->dispatch(new RegisterArticleView($article, $request));

        return new HtmlResponse($this->renderer->render(
            '@site/knowledge-base/article.html.twig',
            [
                'locale' => $locale,
                'article' => $article,
            ]
        ));
    }
}
