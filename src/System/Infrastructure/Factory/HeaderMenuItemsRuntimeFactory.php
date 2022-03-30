<?php

declare(strict_types=1);

namespace Support\System\Infrastructure\Factory;

use Psr\Container\ContainerInterface;
use Support\System\Infrastructure\Twig\HeaderMenuItemsRuntime;

final class HeaderMenuItemsRuntimeFactory
{
    public function __invoke(ContainerInterface $container): HeaderMenuItemsRuntime
    {
        $config = $container->get('config');

        return new HeaderMenuItemsRuntime($config['support'] ?? []);
    }
}
