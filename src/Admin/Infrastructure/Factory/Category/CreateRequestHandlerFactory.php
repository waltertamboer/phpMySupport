<?php

declare(strict_types=1);

namespace Support\Admin\Infrastructure\Factory\Category;

use Doctrine\ORM\EntityManagerInterface;
use Mezzio\Template\TemplateRendererInterface;
use Psr\Container\ContainerInterface;
use Support\Admin\Application\RequestHandler\Category\Create;

final class CreateRequestHandlerFactory
{
    public function __invoke(ContainerInterface $container): Create
    {
        return new Create(
            $container->get(TemplateRendererInterface::class),
            $container->get(EntityManagerInterface::class),
        );
    }
}
