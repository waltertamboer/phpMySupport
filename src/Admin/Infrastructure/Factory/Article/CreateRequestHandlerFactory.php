<?php

declare(strict_types=1);

namespace Support\Admin\Infrastructure\Factory\Article;

use Doctrine\ORM\EntityManagerInterface;
use Mezzio\Template\TemplateRendererInterface;
use Psr\Container\ContainerInterface;
use Support\Admin\Application\RequestHandler\Article\Create;
use Support\System\Domain\I18n\LocaleRepository;
use Support\System\Domain\SettingManager;

final class CreateRequestHandlerFactory
{
    public function __invoke(ContainerInterface $container): Create
    {
        return new Create(
            $container->get(TemplateRendererInterface::class),
            $container->get(EntityManagerInterface::class),
            $container->get(LocaleRepository::class),
            $container->get(SettingManager::class),
        );
    }
}
