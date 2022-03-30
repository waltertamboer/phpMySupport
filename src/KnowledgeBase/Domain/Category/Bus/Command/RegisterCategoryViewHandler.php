<?php

declare(strict_types=1);

namespace Support\KnowledgeBase\Domain\Category\Bus\Command;

use Doctrine\ORM\EntityManagerInterface;
use Support\KnowledgeBase\Domain\Category\CategoryRevisionView;
use Support\System\Domain\Value\IPAddress;
use Support\System\Domain\Value\UserAgent;

final class RegisterCategoryViewHandler
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager
    ) {
    }

    public function __invoke(RegisterCategoryView $command): void
    {
        $ipAddress = IPAddress::fromRequest($command->request);
        $userAgent = UserAgent::fromRequest($command->request);

        $this->entityManager->persist(new CategoryRevisionView(
            $command->category->getLastRevision(),
            $ipAddress,
            $userAgent
        ));

        $this->entityManager->flush();
    }
}
