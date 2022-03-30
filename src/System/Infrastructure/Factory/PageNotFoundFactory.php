<?php

declare(strict_types=1);

namespace Support\System\Infrastructure\Factory;

use Mezzio\Template\TemplateRendererInterface;
use Psr\Container\ContainerInterface;
use Support\System\Application\Middleware\PageNotFound;

final class PageNotFoundFactory
{
    public function __invoke(ContainerInterface $container): PageNotFound
    {
        return new PageNotFound(
            $container->get(TemplateRendererInterface::class),
        );
    }
}
