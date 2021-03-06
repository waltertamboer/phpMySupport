<?php

declare(strict_types=1);

namespace Support\Admin\Application\RequestHandler\Media;

use Doctrine\ORM\EntityManagerInterface;
use Laminas\Diactoros\Response\HtmlResponse;
use Mezzio\Authentication\UserInterface;
use Mezzio\Template\TemplateRendererInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Support\KnowledgeBase\Domain\Media\File;
use Support\System\Application\Exception\ResourceNotFound;

final class Overview implements RequestHandlerInterface
{
    public function __construct(
        private readonly TemplateRendererInterface $renderer,
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $user = $request->getAttribute(UserInterface::class);

        if ($user === null || !$user->isEditor()) {
            throw ResourceNotFound::fromRequest($request);
        }

        $qb = $this->entityManager->createQueryBuilder();
        $qb->select('f');
        $qb->from(File::class, 'f');
        $qb->join('f.lastRevision', 'r');
        $qb->orderBy($qb->expr()->asc('r.name'));

        $files = $qb->getQuery()->getResult();

        return new HtmlResponse($this->renderer->render(
            '@admin/media/overview.html.twig',
            [
                'files' => $files,
            ],
        ));
    }
}
