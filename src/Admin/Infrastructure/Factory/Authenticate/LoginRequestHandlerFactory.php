<?php

declare(strict_types=1);

namespace Support\Admin\Infrastructure\Factory\Authenticate;

use Mezzio\Authentication\AuthenticationInterface;
use Mezzio\Template\TemplateRendererInterface;
use Psr\Container\ContainerInterface;
use Support\Admin\Application\RequestHandler\Authentication\Login;

final class LoginRequestHandlerFactory
{
    public function __invoke(ContainerInterface $container): Login
    {
        return new Login(
            $container->get(TemplateRendererInterface::class),
            $container->get(AuthenticationInterface::class),
        );
    }
}
