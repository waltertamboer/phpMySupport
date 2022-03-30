<?php

declare(strict_types=1);

namespace Support\KnowledgeBase\Infrastructure\Factory;

use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;
use Support\KnowledgeBase\Domain\Article\Bus\Command\RegisterArticleViewHandler;

final class RegisterArticleViewHandlerFactory
{
    public function __invoke(ContainerInterface $container): RegisterArticleViewHandler
    {
        return new RegisterArticleViewHandler(
            $container->get(EntityManagerInterface::class),
        );
    }
}
