<?php

declare(strict_types=1);

namespace Support\Admin\Application\RequestHandler\Authentication;

use Laminas\Diactoros\Response\HtmlResponse;
use Mezzio\Authentication\UserInterface;
use Mezzio\Template\TemplateRendererInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class Logout implements RequestHandlerInterface
{
    public function __construct(
        private readonly TemplateRendererInterface $renderer,
    ) {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $session = $request->getAttribute('session');
        $session->unset(UserInterface::class);

        return new HtmlResponse($this->renderer->render(
            '@admin/authentication/logout.html.twig',
            [],
        ));
    }
}
