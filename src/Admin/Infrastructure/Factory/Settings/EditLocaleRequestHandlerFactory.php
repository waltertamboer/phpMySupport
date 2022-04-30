<?php

declare(strict_types=1);

namespace Support\Admin\Infrastructure\Factory\Settings;

use Doctrine\ORM\EntityManagerInterface;
use Mezzio\Template\TemplateRendererInterface;
use Psr\Container\ContainerInterface;
use Support\Admin\Application\RequestHandler\Settings\EditLocale;

final class EditLocaleRequestHandlerFactory
{
    public function __invoke(ContainerInterface $container): EditLocale
    {
        return new EditLocale(
            $container->get(TemplateRendererInterface::class),
            $container->get(EntityManagerInterface::class),
        );
    }
}
