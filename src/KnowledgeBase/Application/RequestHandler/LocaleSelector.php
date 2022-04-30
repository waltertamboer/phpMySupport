<?php

declare(strict_types=1);

namespace Support\KnowledgeBase\Application\RequestHandler;

use Laminas\Diactoros\Response\HtmlResponse;
use Laminas\Diactoros\Response\RedirectResponse;
use Mezzio\Helper\UrlHelper;
use Mezzio\Template\TemplateRendererInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Support\System\Domain\Bus\Query\QueryBus;
use Support\System\Domain\I18n\Bus\Query\GetUsedLocales;
use Support\System\Domain\I18n\UsedLocale;

final class LocaleSelector implements RequestHandlerInterface
{
    public function __construct(
        private readonly UrlHelper $urlHelper,
        private readonly TemplateRendererInterface $renderer,
        private readonly QueryBus $queryBus,
    ) {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $locales = $this->queryBus->query(new GetUsedLocales());

        if ($locales->count() === 1) {
            $locale = $locales->get(0);
            assert($locale instanceof UsedLocale);

            return new RedirectResponse(
                $this->urlHelper->generate('category-overview', [
                    'locale' => $locale->getSlug(),
                ]),
            );
        }

        $response = new HtmlResponse($this->renderer->render(
            '@site/locale-selector.html.twig',
            [
                'locales' => $locales,
            ]
        ));

        return $response;
    }
}
