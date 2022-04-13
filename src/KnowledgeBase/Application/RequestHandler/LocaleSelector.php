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
use Support\System\Domain\I18n\LocaleRepository;
use Support\System\Domain\SettingManager;

final class LocaleSelector implements RequestHandlerInterface
{
    public function __construct(
        private readonly UrlHelper $urlHelper,
        private readonly TemplateRendererInterface $renderer,
        private readonly QueryBus $queryBus,
        private readonly LocaleRepository $localeRepository,
        private readonly SettingManager $settingManager,
    ) {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $locales = $this->localeRepository->getUsedLocales();

        if ($locales->count() === 1) {
            return new RedirectResponse(
                $this->urlHelper->generate('category-overview', [
                    'locale' => $locales->get(0)->getId(),
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
