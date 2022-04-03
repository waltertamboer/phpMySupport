<?php

declare(strict_types=1);

namespace Support\System\Infrastructure\Factory;

use Mezzio\Template\TemplateRendererInterface;
use Psr\Container\ContainerInterface;
use Support\System\Infrastructure\Twig\DefaultTemplateParamsMiddleware;

final class DefaultTemplateParamsMiddlewareFactory
{
    public function __invoke(ContainerInterface $container): DefaultTemplateParamsMiddleware
    {
        return new DefaultTemplateParamsMiddleware(
            $container->get(TemplateRendererInterface::class),
        );
    }
}
