<?php

declare(strict_types=1);

namespace Support\KnowledgeBase\Infrastructure\Factory;

use Mezzio\Helper\UrlHelper;
use Mezzio\Template\TemplateRendererInterface;
use Psr\Container\ContainerInterface;
use Support\KnowledgeBase\Application\RequestHandler\LocaleSelector;
use Support\System\Domain\Bus\Query\QueryBus;
use Support\System\Domain\I18n\LocaleRepository;
use Support\System\Domain\SettingManager;

final class LocaleSelectorRequestHandlerFactory
{
    public function __invoke(ContainerInterface $container): LocaleSelector
    {
        return new LocaleSelector(
            $container->get(UrlHelper::class),
            $container->get(TemplateRendererInterface::class),
            $container->get(QueryBus::class),
            $container->get(LocaleRepository::class),
            $container->get(SettingManager::class),
        );
    }
}
