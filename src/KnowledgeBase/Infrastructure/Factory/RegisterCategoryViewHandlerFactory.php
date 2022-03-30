<?php

declare(strict_types=1);

namespace Support\KnowledgeBase\Infrastructure\Factory;

use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;
use Support\KnowledgeBase\Domain\Category\Bus\Command\RegisterCategoryViewHandler;

final class RegisterCategoryViewHandlerFactory
{
    public function __invoke(ContainerInterface $container): RegisterCategoryViewHandler
    {
        return new RegisterCategoryViewHandler(
            $container->get(EntityManagerInterface::class),
        );
    }
}
