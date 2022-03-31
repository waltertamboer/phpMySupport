<?php

declare(strict_types=1);

namespace Support\System\Infrastructure\Factory;

use Psr\Container\ContainerInterface;
use Support\System\Domain\ApplicationConfig;

final class ApplicationConfigFactory
{
    public function __invoke(ContainerInterface $container): ApplicationConfig
    {
        $config = $container->get('config');

        return new ApplicationConfig((array)($config['phpsupport'] ?? []));
    }
}
