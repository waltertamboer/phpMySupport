<?php

declare(strict_types=1);

namespace Support\KnowledgeBase\Application\RequestHandler;

use Laminas\Diactoros\Response\HtmlResponse;
use Mezzio\Template\TemplateRendererInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Support\KnowledgeBase\Domain\Category\Bus\Query\GetCategoryOverview;
use Support\System\Domain\Bus\Query\QueryBus;

final class Homepage implements RequestHandlerInterface
{
    public function __construct(
        private readonly TemplateRendererInterface $renderer,
        private readonly QueryBus $queryBus,
    ) {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $categories = $this->queryBus->query(new GetCategoryOverview());

        return new HtmlResponse($this->renderer->render(
            '@homepage/homepage.html.twig',
            [
                'categories' => $categories,
            ]
        ));
    }
}
