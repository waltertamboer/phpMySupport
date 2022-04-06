<?php

declare(strict_types=1);

namespace Support\Admin\Infrastructure\Factory\Settings;

use Doctrine\ORM\EntityManagerInterface;
use Mezzio\Template\TemplateRendererInterface;
use Psr\Container\ContainerInterface;
use Support\Admin\Application\RequestHandler\Settings\ExportImport;

final class ExportImportRequestHandlerFactory
{
    public function __invoke(ContainerInterface $container): ExportImport
    {
        return new ExportImport(
            $container->get(TemplateRendererInterface::class),
            $container->get(EntityManagerInterface::class),
        );
    }
}
