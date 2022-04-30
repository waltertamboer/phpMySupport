<?php

declare(strict_types=1);

namespace Support\Admin\Infrastructure\Factory\Settings;

use Doctrine\ORM\EntityManagerInterface;
use Mezzio\Template\TemplateRendererInterface;
use Psr\Container\ContainerInterface;
use Support\Admin\Application\RequestHandler\Settings\AddLocale;

final class AddLocaleRequestHandlerFactory
{
    public function __invoke(ContainerInterface $container): AddLocale
    {
        return new AddLocale(
            $container->get(TemplateRendererInterface::class),
            $container->get(EntityManagerInterface::class),
        );
    }
}
