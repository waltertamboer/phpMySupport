<?php

declare(strict_types=1);

namespace Support\System\Application\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Support\System\Domain\SettingManager;

final class SettingsMiddleware implements MiddlewareInterface
{
    private readonly SettingManager $settingManager;

    public function __construct(SettingManager $settingManager)
    {
        $this->settingManager = $settingManager;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $request = $request->withAttribute(
            'searchEnabled',
            $this->settingManager->get('searchEnabled') === '1'
        );

        $request = $request->withAttribute(
            'ticketsEnabled',
            $this->settingManager->get('ticketsEnabled') === '1'
        );

        $request = $request->withAttribute(
            'localizationEnabled',
            $this->settingManager->get('localizationEnabled') === '1'
        );

        return $handler->handle($request);
    }
}
