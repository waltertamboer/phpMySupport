<?php

declare(strict_types=1);

namespace Support\Admin\Infrastructure\Factory\Media;

use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;
use Support\Admin\Application\RequestHandler\Media\TinyMceDialog;

final class TinyMceDialogHandlerFactory
{
    public function __invoke(ContainerInterface $container): TinyMceDialog
    {
        return new TinyMceDialog(
            $container->get(EntityManagerInterface::class),
        );
    }
}
