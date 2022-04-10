<?php

declare(strict_types=1);

namespace Support\System\Infrastructure\Laminas;

use Doctrine\ORM\EntityManagerInterface;
use Mezzio\Application;
use Mezzio\Authentication\AuthenticationInterface;
use Mezzio\Authentication\Session\PhpSession;
use Mezzio\Authentication\UserRepositoryInterface;
use Mezzio\Container\ApplicationConfigInjectionDelegator;
use Support\System\Application\Middleware\LocalizationMiddleware;
use Support\System\Application\Middleware\PageNotFound;
use Support\System\Application\Middleware\SettingsMiddleware;
use Support\System\Domain\ApplicationConfig;
use Support\System\Domain\Bus\Command\CommandBus;
use Support\System\Domain\Bus\Query\QueryBus;
use Support\System\Domain\I18n\LocaleRepository;
use Support\System\Domain\SettingManager;
use Support\System\Infrastructure\Doctrine\ORM\EntityManagerFactory;
use Support\System\Infrastructure\Doctrine\ORM\MezzioAuthenticationUserRepository;
use Support\System\Infrastructure\Doctrine\ORM\MezzioAuthenticationUserRepositoryFactory;
use Support\System\Infrastructure\Factory;
use Support\System\Infrastructure\Twig\ApplicationRuntime;
use Support\System\Infrastructure\Twig\DefaultTemplateParamsMiddleware;
use Twig\Environment;
use Twig\Extra\Intl\IntlExtension;

final class ConfigProvider
{
    public function __invoke(): array
    {
        return [
            'authentication' => $this->getAuthentication(),
            'dependencies' => $this->getDependencies(),
            'doctrine' => $this->getDoctrine(),
            'templates' => $this->getTemplates(),
            'twig' => $this->getTwig(),
        ];
    }

    private function getAuthentication(): array
    {
        return [
            'redirect' => '/admin/login',
        ];
    }

    private function getDependencies(): array
    {
        return [
            'aliases' => [
                AuthenticationInterface::class => PhpSession::class,
                UserRepositoryInterface::class => MezzioAuthenticationUserRepository::class,
            ],
            'delegators' => [
                Application::class => [
                    ApplicationConfigInjectionDelegator::class,
                ],
                Environment::class => [
                    Factory\TwigEnvironmentDelegator::class,
                ],
            ],
            'factories' => [
                ApplicationConfig::class => Factory\ApplicationConfigFactory::class,
                ApplicationRuntime::class => Factory\ApplicationRuntimeFactory::class,
                CommandBus::class => Factory\CommandBusFactory::class,
                DefaultTemplateParamsMiddleware::class => Factory\DefaultTemplateParamsMiddlewareFactory::class,
                EntityManagerInterface::class => EntityManagerFactory::class,
                LocalizationMiddleware::class => Factory\LocalizationMiddlewareFactory::class,
                MezzioAuthenticationUserRepository::class => MezzioAuthenticationUserRepositoryFactory::class,
                PageNotFound::class => Factory\PageNotFoundFactory::class,
                QueryBus::class => Factory\QueryBusFactory::class,
                SettingManager::class => Factory\SettingManagerFactory::class,
                SettingsMiddleware::class => Factory\SettingsMiddlewareFactory::class,
                LocaleRepository::class => Factory\LocaleRepositoryFactory::class,
            ],
        ];
    }

    private function getDoctrine(): array
    {
        return [
            'migrations' => [
                'migrations_paths' => [
                    'Support\System\Infrastructure\Doctrine\Migration' => __DIR__ . '/../Doctrine/Migration/',
                ],
            ],
            'metadata' => [
                'type' => 'xml',
                'paths' => [
                    __DIR__ . '/../Doctrine/ORM/',
                ],
            ],
        ];
    }

    private function getTemplates(): array
    {
        return [
            'extension' => 'html.twig',
            'paths' => [], // Do not populate this, the paths are populated in the TwigEnvironmentDelegator
        ];
    }

    private function getTwig(): array
    {
        return [
            'autoescape' => 'html', // Auto-escaping strategy [html|js|css|url|false]
            'cache_dir' => 'data/cache/templates-cache/',
            'assets_url' => '/',
            'assets_version' => 'v1',
            'extensions' => [
                ApplicationRuntime::class,
                new IntlExtension(),
            ],
            'globals' => [],
            'optimizations' => -1, // -1: Enable all (default), 0: disable optimizations
            'runtime_loaders' => [],
            'timezone' => 'Europe/Amsterdam',
            'auto_reload' => true, // Recompile the template whenever the source code changes
        ];
    }
}
