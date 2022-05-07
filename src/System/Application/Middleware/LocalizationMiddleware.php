<?php

declare(strict_types=1);

namespace Support\System\Application\Middleware;

use Locale;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Support\System\Domain\I18n\UsedLocaleRepository;
use Support\System\Domain\SettingManager;

final class LocalizationMiddleware implements MiddlewareInterface
{
    public const LOCALIZATION_ATTRIBUTE = 'locale';
    public const USED_LOCALIZATION_ATTRIBUTE = 'usedLocale';

    public function __construct(
        private readonly SettingManager $settingManager,
        private readonly UsedLocaleRepository $usedLocaleRepository,
    ) {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler) : ResponseInterface
    {
        $defaultLocale = $this->settingManager->get('defaultLocale', 'en_US');

        $locale = $request->getAttribute(
            'locale',
            Locale::acceptFromHttp(
                $request->getServerParams()['HTTP_ACCEPT_LANGUAGE'] ?? $defaultLocale
            )
        );

        $usedLocale = $this->usedLocaleRepository->findBySlug($locale);

        return $handler->handle(
            $request
                ->withAttribute(self::LOCALIZATION_ATTRIBUTE, $locale)
                ->withAttribute(self::USED_LOCALIZATION_ATTRIBUTE, $usedLocale)
        );
    }
}
