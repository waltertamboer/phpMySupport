<?php

declare(strict_types=1);

namespace Support\Admin\Infrastructure\Factory\Article;

use Doctrine\ORM\EntityManagerInterface;
use Mezzio\Template\TemplateRendererInterface;
use Psr\Container\ContainerInterface;
use Support\Admin\Application\RequestHandler\Article\Update;

final class UpdateRequestHandlerFactory
{
    public function __invoke(ContainerInterface $container): Update
    {
        return new Update(
            $container->get(TemplateRendererInterface::class),
            $container->get(EntityManagerInterface::class),
        );
    }
}
