<?php

declare(strict_types=1);

namespace Support\Admin\Infrastructure\Factory\Dashboard;

use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;
use Support\Admin\Application\RequestHandler\Dashboard\View;
use Mezzio\Template\TemplateRendererInterface;
use Support\System\Domain\I18n\LocaleQueryRepository;

final class ViewRequestHandlerFactory
{
    public function __invoke(ContainerInterface $container): View
    {
        return new View(
            $container->get(TemplateRendererInterface::class),
            $container->get(EntityManagerInterface::class),
            $container->get(LocaleQueryRepository::class),
        );
    }
}
