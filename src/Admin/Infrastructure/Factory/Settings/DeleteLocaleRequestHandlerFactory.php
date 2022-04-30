<?php

declare(strict_types=1);

namespace Support\Admin\Infrastructure\Factory\Settings;

use Doctrine\ORM\EntityManagerInterface;
use Mezzio\Template\TemplateRendererInterface;
use Psr\Container\ContainerInterface;
use Support\Admin\Application\RequestHandler\Settings\DeleteLocale;
use Support\Admin\Application\RequestHandler\Settings\Locales;

final class DeleteLocaleRequestHandlerFactory
{
    public function __invoke(ContainerInterface $container): DeleteLocale
    {
        return new DeleteLocale(
            $container->get(TemplateRendererInterface::class),
            $container->get(EntityManagerInterface::class),
        );
    }
}
