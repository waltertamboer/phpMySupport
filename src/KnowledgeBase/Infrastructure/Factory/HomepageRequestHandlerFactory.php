<?php

declare(strict_types=1);

namespace Support\KnowledgeBase\Infrastructure\Factory;

use Mezzio\Template\TemplateRendererInterface;
use Psr\Container\ContainerInterface;
use Support\KnowledgeBase\Application\RequestHandler\Homepage;
use Support\System\Domain\Bus\Query\QueryBus;
use Support\System\Domain\SettingManager;

final class HomepageRequestHandlerFactory
{
    public function __invoke(ContainerInterface $container): Homepage
    {
        return new Homepage(
            $container->get(TemplateRendererInterface::class),
            $container->get(QueryBus::class),
            $container->get(SettingManager::class),
        );
    }
}
