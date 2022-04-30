<?php

declare(strict_types=1);

namespace Support\KnowledgeBase\Infrastructure\Factory;

use Doctrine\ORM\EntityManagerInterface;
use Mezzio\Template\TemplateRendererInterface;
use Psr\Container\ContainerInterface;
use Support\KnowledgeBase\Application\RequestHandler\CreateTicket;

final class CreateTicketRequestHandlerFactory
{
    public function __invoke(ContainerInterface $container): CreateTicket
    {
        return new CreateTicket(
            $container->get(TemplateRendererInterface::class),
            $container->get(EntityManagerInterface::class),
        );
    }
}
