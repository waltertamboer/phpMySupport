<?php

declare(strict_types=1);

namespace Support\KnowledgeBase\Application\RequestHandler;

use Laminas\Diactoros\Response\HtmlResponse;
use Laminas\Diactoros\Response\RedirectResponse;
use Mezzio\Template\TemplateRendererInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Support\KnowledgeBase\Domain\Category\Bus\Query\GetCategoryOverview;
use Support\System\Application\Exception\ResourceNotFound;
use Support\System\Application\Middleware\LocalizationMiddleware;
use Support\System\Domain\Bus\Query\QueryBus;
use Support\System\Domain\I18n\Bus\Query\GetUsedLocaleBySlug;
use Support\System\Domain\SettingManager;

final class CategoryOverview implements RequestHandlerInterface
{
    public function __construct(
        private readonly TemplateRendererInterface $renderer,
        private readonly QueryBus $queryBus,
        private readonly SettingManager $settingManager,
    ) {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $routeLocale = $request->getAttribute('locale');

        if ($routeLocale === null) {
            $defaultLocale = $this->settingManager->get('defaultLocale', 'en_US');

            return new RedirectResponse('/' . $defaultLocale);
        }

        $locale = $request->getAttribute(LocalizationMiddleware::USED_LOCALIZATION_ATTRIBUTE);

        if ($locale === null) {
            throw ResourceNotFound::fromRequest($request);
        }

        $categories = $this->queryBus->query(new GetCategoryOverview($locale));

        $response = new HtmlResponse($this->renderer->render(
            '@site/knowledge-base/category-overview.html.twig',
            [
                'locale' => $routeLocale,
                'usedLocale' => $locale,
                'categories' => $categories,
            ]
        ));

        return $response;
    }
}
