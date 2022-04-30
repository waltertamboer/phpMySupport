<?php

declare(strict_types=1);

namespace Support\Admin\Infrastructure\Factory\Settings;

use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;
use Support\Admin\Application\RequestHandler\Settings\UsedLocalesXhr;

final class UsedLocalesXhrRequestHandlerFactory
{
    public function __invoke(ContainerInterface $container): UsedLocalesXhr
    {
        return new UsedLocalesXhr(
            $container->get(EntityManagerInterface::class),
        );
    }
}
