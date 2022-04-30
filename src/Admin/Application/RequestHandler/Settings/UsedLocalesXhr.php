<?php

declare(strict_types=1);

namespace Support\Admin\Application\RequestHandler\Settings;

use Doctrine\ORM\EntityManagerInterface;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Support\System\Domain\I18n\UsedLocale;
use Support\System\Domain\Value\UnicodeString;

final class UsedLocalesXhr implements RequestHandlerInterface
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
        $qb->select('e.id, e.name');
        $qb->from(UsedLocale::class, 'e');
        $qb->where($qb->expr()->like('LOWER(e.name)', ':query'));
        $qb->setParameter('query', '%' . $query->toLowercase()->value() . '%');

        $locales = $qb->getQuery()->getArrayResult();

        return new JsonResponse($locales);
    }
}
