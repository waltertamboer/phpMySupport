<?php

declare(strict_types=1);

namespace Support\KnowledgeBase\Infrastructure\Factory;

use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;
use Support\KnowledgeBase\Application\RequestHandler\MediaFile;

final class MediaFileRequestHandlerFactory
{
    public function __invoke(ContainerInterface $container): MediaFile
    {
        return new MediaFile(
            $container->get(EntityManagerInterface::class),
        );
    }
}
