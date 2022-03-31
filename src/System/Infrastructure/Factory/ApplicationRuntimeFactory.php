<?php

declare(strict_types=1);

namespace Support\System\Infrastructure\Factory;

use Psr\Container\ContainerInterface;
use Support\System\Domain\ApplicationConfig;
use Support\System\Infrastructure\Twig\ApplicationRuntime;

final class ApplicationRuntimeFactory
{
    public function __invoke(ContainerInterface $container): ApplicationRuntime
    {
        $config = $container->get(ApplicationConfig::class);

        return new ApplicationRuntime($config);
    }
}
