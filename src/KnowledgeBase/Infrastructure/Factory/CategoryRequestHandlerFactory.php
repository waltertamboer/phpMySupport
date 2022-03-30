<?php

declare(strict_types=1);

namespace Support\KnowledgeBase\Infrastructure\Factory;

use Mezzio\Template\TemplateRendererInterface;
use Psr\Container\ContainerInterface;
use Support\KnowledgeBase\Application\RequestHandler\Category;
use Support\System\Domain\Bus\Command\CommandBus;
use Support\System\Domain\Bus\Query\QueryBus;

final class CategoryRequestHandlerFactory
{
    public function __invoke(ContainerInterface $container): Category
    {
        return new Category(
            $container->get(TemplateRendererInterface::class),
            $container->get(CommandBus::class),
            $container->get(QueryBus::class),
        );
    }
}
