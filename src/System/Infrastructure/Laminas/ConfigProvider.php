<?php

declare(strict_types=1);

namespace Support\System\Infrastructure\Laminas;

use Doctrine\ORM\EntityManagerInterface;
use Mezzio\Application;
use Mezzio\Authentication\AuthenticationInterface;
use Mezzio\Authentication\Session\PhpSession;
use Mezzio\Authentication\UserInterface;
use Mezzio\Authentication\UserRepository\Htpasswd;
use Mezzio\Authentication\UserRepositoryInterface;
use Mezzio\Container\ApplicationConfigInjectionDelegator;
use Support\System\Application\Middleware\PageNotFound;
use Support\System\Domain\Bus\Command\CommandBus;
use Support\System\Domain\Bus\Query\QueryBus;
use Support\System\Infrastructure\Doctrine\ORM\EntityManagerFactory;
use Support\System\Infrastructure\Doctrine\ORM\MezzioAuthenticationUserRepository;
use Support\System\Infrastructure\Doctrine\ORM\MezzioAuthenticationUserRepositoryFactory;
use Support\System\Infrastructure\Factory;
use Support\System\Infrastructure\Twig\HeaderMenuItemsRuntime;

final class ConfigProvider
{
    public function __invoke(): array
    {
        return [
            'authentication' => $this->getAuthentication(),
            'dependencies' => $this->getDependencies(),
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
            ],
            'factories' => [
                CommandBus::class => Factory\CommandBusFactory::class,
                MezzioAuthenticationUserRepository::class => MezzioAuthenticationUserRepositoryFactory::class,
                HeaderMenuItemsRuntime::class => Factory\HeaderMenuItemsRuntimeFactory::class,
                EntityManagerInterface::class => EntityManagerFactory::class,
                PageNotFound::class => Factory\PageNotFoundFactory::class,
                QueryBus::class => Factory\QueryBusFactory::class,
            ],
        ];
    }

    private function getTemplates(): array
    {
        return [
            'extension' => 'html.twig',
            'paths' => [
                'admin' => [ 'data/themes/default/admin' ],
                'error' => [ 'data/themes/default/error' ],
                'homepage' => [ 'data/themes/default/homepage' ],
                'knowledge-base' => [ 'data/themes/default/knowledge-base' ],
                'layout' => [ 'data/themes/default/layout' ],
            ],
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
                HeaderMenuItemsRuntime::class,
            ],
            'globals' => [],
            'optimizations' => -1, // -1: Enable all (default), 0: disable optimizations
            'runtime_loaders' => [],
            'timezone' => 'Europe/Amsterdam',
            'auto_reload' => true, // Recompile the template whenever the source code changes
        ];
    }
}