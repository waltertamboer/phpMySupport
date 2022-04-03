<?php

declare(strict_types=1);

namespace Support\System\Infrastructure\Twig;

use Mezzio\Authentication\UserInterface;
use Mezzio\Template\TemplateRendererInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class DefaultTemplateParamsMiddleware implements MiddlewareInterface
{
    public function __construct(
        private readonly TemplateRendererInterface $templateRenderer,
    ) {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $user = $request->getAttribute(UserInterface::class);

        $this->templateRenderer->addDefaultParam(
            TemplateRendererInterface::TEMPLATE_ALL,
            'authenticatedUser',
            $user,
        );

        return $handler->handle($request);
    }
}
