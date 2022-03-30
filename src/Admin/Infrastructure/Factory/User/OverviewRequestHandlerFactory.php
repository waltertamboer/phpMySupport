<?php

declare(strict_types=1);

namespace Support\Admin\Infrastructure\Factory\User;

use Doctrine\ORM\EntityManagerInterface;
use Mezzio\Template\TemplateRendererInterface;
use Psr\Container\ContainerInterface;
use Support\Admin\Application\RequestHandler\User\Overview;

final class OverviewRequestHandlerFactory
{
    public function __invoke(ContainerInterface $container): Overview
    {
        return new Overview(
            $container->get(TemplateRendererInterface::class),
            $container->get(EntityManagerInterface::class),
        );
    }
}
