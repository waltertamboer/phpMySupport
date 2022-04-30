<?php

declare(strict_types=1);

namespace Support\Admin\Application\RequestHandler\Media;

use Doctrine\ORM\EntityManagerInterface;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Support\KnowledgeBase\Domain\Media\File;
use Support\System\Domain\Value\UnicodeString;

final class MediaXhr implements RequestHandlerInterface
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $queryParams = $request->getQueryParams();
        $query = new UnicodeString($queryParams['q'] ?? '');

        $qb = $this->entityManager->createQueryBuilder();
        $qb->select([
            'f.id',
            'r.name',
        ]);
        $qb->from(File::class, 'f');
        $qb->join('f.lastRevision', 'r');
        $qb->where($qb->expr()->like('LOWER(r.name)', ':query'));
        $qb->setParameter('query', '%' . $query->toLowercase()->value() . '%');

        $files = $qb->getQuery()->getArrayResult();

        return new JsonResponse($files);
    }
}
