<?php

declare(strict_types=1);

namespace Support\Admin\Infrastructure\Factory\Authenticate;

use Psr\Container\ContainerInterface;
use Support\Admin\Application\RequestHandler\Authentication\Logout;
use Mezzio\Template\TemplateRendererInterface;

final class LogoutRequestHandlerFactory
{
    public function __invoke(ContainerInterface $container): Logout
    {
        return new Logout(
            $container->get(TemplateRendererInterface::class),
        );
    }
}
