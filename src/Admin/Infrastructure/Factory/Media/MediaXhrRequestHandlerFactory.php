<?php

declare(strict_types=1);

namespace Support\Admin\Infrastructure\Factory\Media;

use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;
use Support\Admin\Application\RequestHandler\Media\MediaXhr;

final class MediaXhrRequestHandlerFactory
{
    public function __invoke(ContainerInterface $container): MediaXhr
    {
        return new MediaXhr(
            $container->get(EntityManagerInterface::class),
        );
    }
}
