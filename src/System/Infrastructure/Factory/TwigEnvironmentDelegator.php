<?php

declare(strict_types=1);

namespace Support\System\Infrastructure\Factory;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\DelegatorFactoryInterface;
use Mezzio\Twig\TwigRendererFactory;
use Support\System\Domain\ApplicationConfig;
use Support\System\Domain\SettingManager;
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
        $settingManager = $container->get(SettingManager::class);
        assert($settingManager instanceof SettingManager);

        $loader = new FilesystemLoader();
        $loader->addPath(sprintf(
            'data/themes/site/%s',
            $settingManager->get('theme')
        ), 'site');

        $loader->addPath(sprintf(
            'data/themes/admin/%s',
            $settingManager->get('themeAdmin')
        ), 'admin');

        return $loader;
    }
}
