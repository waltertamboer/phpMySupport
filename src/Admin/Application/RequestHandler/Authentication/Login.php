<?php

declare(strict_types=1);

namespace Support\Admin\Application\RequestHandler\Authentication;

use Laminas\Diactoros\Response\HtmlResponse;
use Laminas\Diactoros\Response\RedirectResponse;
use Laminas\Diactoros\Uri;
use Mezzio\Authentication\Session\PhpSession;
use Mezzio\Authentication\UserInterface;
use Mezzio\Session\SessionInterface;
use Mezzio\Template\TemplateRendererInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class Login implements RequestHandlerInterface
{
    public function __construct(
        private readonly TemplateRendererInterface $renderer,
        private readonly PhpSession $adapter,
    ) {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $session = $request->getAttribute('session');

        if ('POST' === $request->getMethod()) {
            return $this->handleLoginAttempt($request, $session);
        }

        $session->unset(UserInterface::class);

        return new HtmlResponse($this->renderer->render(
            '@admin/authentication/login.html.twig',
            [
                'error' => false,
                'username' => '',
            ],
        ));
    }

    private function handleLoginAttempt(
        ServerRequestInterface $request,
        SessionInterface $session,
    ): ResponseInterface
    {
        // User session takes precedence over user/pass POST in
        // the auth adapter so we remove the session prior
        // to auth attempt
        $session->unset(UserInterface::class);

        // Login was successful
        if ($this->adapter->authenticate($request)) {
            return new RedirectResponse('/admin/dashboard');
        }

        $formData = $request->getParsedBody();

        // Login failed
        return new HtmlResponse($this->renderer->render(
            '@admin/authentication/login.html.twig',
            [
                'error' => true,
                'username' => $formData['username'] ?? ''
            ],
        ));
    }
}
