<?php

declare(strict_types=1);

namespace Support\KnowledgeBase\Domain\Article\Bus\Command;

use Doctrine\ORM\EntityManagerInterface;
use Support\KnowledgeBase\Domain\Article\ArticleRevisionView;
use Support\System\Domain\Value\IPAddress;
use Support\System\Domain\Value\UserAgent;

final class RegisterArticleViewHandler
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager
    ) {
    }

    public function __invoke(RegisterArticleView $command): void
    {
        $ipAddress = IPAddress::fromRequest($command->request);
        $userAgent = UserAgent::fromRequest($command->request);

        $this->entityManager->persist(new ArticleRevisionView(
            $command->article->getLastRevision(),
            $ipAddress,
            $userAgent
        ));

        $this->entityManager->flush();
    }
}
