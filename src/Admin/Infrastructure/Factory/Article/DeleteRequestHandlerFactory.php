<?php

declare(strict_types=1);

namespace Support\Admin\Infrastructure\Factory\Article;

use Doctrine\ORM\EntityManagerInterface;
use Mezzio\Template\TemplateRendererInterface;
use Psr\Container\ContainerInterface;
use Support\Admin\Application\RequestHandler\Article\Delete;

final class DeleteRequestHandlerFactory
{
    public function __invoke(ContainerInterface $container): Delete
    {
        return new Delete(
            $container->get(TemplateRendererInterface::class),
            $container->get(EntityManagerInterface::class),
        );
    }
}
