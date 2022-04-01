<?php

declare(strict_types=1);

namespace Support\System\Infrastructure\Factory;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\DelegatorFactoryInterface;
use Mezzio\Twig\TwigRendererFactory;
use Support\System\Domain\ApplicationConfig;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Twig\Loader\LoaderInterface;

final class TwigEnvironmentDelegator implements DelegatorFactoryInterface
{
    public function __invoke(ContainerInterface $container, $name, callable $callback, ?array $options = null)
    {
        $result = $callback();
        assert($result instanceof Environment);

        $result->setLoader($this->createLoader($container));

        return $result;
    }

    private function createLoader(ContainerInterface $container): LoaderInterface
    {
        $config = $container->get(ApplicationConfig::class);
        assert($config instanceof ApplicationConfig);

        $loader = new FilesystemLoader();

        $loader->addPath(sprintf(
            'data/themes/site/%s',
            $config->getSiteTheme()
        ), 'site');

        $loader->addPath(sprintf(
            'data/themes/admin/%s',
            $config->getSiteThemeAdmin()
        ), 'admin');

        $loader->addPath(sprintf(
            'data/themes/site/%s/error',
            $config->getSiteTheme()
        ), 'error');

        return $loader;
    }
}
