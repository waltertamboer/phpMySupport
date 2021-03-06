<?php

declare(strict_types=1);

namespace Support\Admin\Application\RequestHandler\Category;

use Doctrine\ORM\EntityManagerInterface;
use Laminas\Diactoros\Response\HtmlResponse;
use Laminas\Diactoros\Response\RedirectResponse;
use Mezzio\Authentication\UserInterface;
use Mezzio\Template\TemplateRendererInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Ramsey\Uuid\Uuid;
use Support\KnowledgeBase\Domain\Category\Category;
use Support\KnowledgeBase\Domain\Media\File;
use Support\System\Application\Exception\ResourceNotFound;
use Support\System\Domain\I18n\LocaleQueryRepository;
use Support\System\Domain\I18n\UsedLocale;

final class Update implements RequestHandlerInterface
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

        $entityId = $request->getAttribute('id');
        $entity = $this->entityManager->find(Category::class, $entityId);

        if ($entity === null) {
            throw ResourceNotFound::fromRequest($request);
        }

        $locale = $entity->getLastRevision()->getLocale();
        $locale = $locale === null ? null : [
            'id' => $locale->getId()->toString(),
            'name' => $locale->getName(),
        ];

        $formData = [
            'name' => $entity->getLastRevision()->getName(),
            'slug' => $entity->getLastRevision()->getSlug(),
            'locale' => $locale,
            'thumbnail' => $entity->getLastRevision()->getThumbnail()?->getId(),
        ];

        $error = false;
        $errorMsg = null;

        if ($request->getMethod() === 'POST') {
            $formData = $request->getParsedBody();

            $formName = $formData['name'] ?? '';
            $formSlug = $formData['slug'] ?? '';

            $formLocale = $this->loadLocale($formData['locale']);
            $formData['locale'] = $formLocale === null ? null : [
                'id' => $formLocale->getId()->toString(),
                'name' => $formLocale->getName(),
            ];

            if ($formName === '') {
                $error = true;
                $errorMsg = 'No name provided.';
            } elseif ($formName !== $entity->getLastRevision()->getName() && $this->hasExistingName($formName)) {
                $error = true;
                $errorMsg = 'The category already exists.';
            } elseif ($formSlug === '') {
                $error = true;
                $errorMsg = 'No slug provided.';
            } elseif ($formSlug !== $entity->getLastRevision()->getSlug() && $this->hasExistingSlug($formSlug)) {
                $error = true;
                $errorMsg = 'The slug already exists.';
            } elseif ($formLocale === null) {
                $error = true;
                $errorMsg = 'No locale provided.';
            } else {
                $revision = $entity->createRevision($user, $formLocale, $formName, $formSlug);
                $revision->setThumbnail($this->loadThumbnail($formData['thumbnail']));

                $this->entityManager->flush();

                return new RedirectResponse('/admin/categories');
            }
        }

        return new HtmlResponse($this->renderer->render(
            '@admin/category/update.html.twig',
            [
                'entity' => $entity,
                'formData' => $formData,
                'mediaFiles' => $this->loadMediaFiles(),
                'error' => $error,
                'errorMsg' => $errorMsg,
            ],
        ));
    }

    private function loadMediaFiles(): array
    {
        $qb = $this->entityManager->createQueryBuilder();
        $qb->select('f');
        $qb->from(File::class, 'f');
        $qb->join('f.lastRevision', 'r');
        $qb->orderBy($qb->expr()->asc('r.name'));

        return $qb->getQuery()->getResult();
    }

    private function loadLocale(?string $id): ?UsedLocale
    {
        if ($id === null || $id === '' || !Uuid::isValid($id)) {
            return null;
        }

        return $this->entityManager->find(UsedLocale::class, $id);
    }

    private function loadThumbnail(?string $id): ?File
    {
        if ($id === null || $id === '') {
            return null;
        }

        return $this->entityManager->find(File::class, $id);
    }

    private function hasExistingName(string $name): bool
    {
        $qb = $this->entityManager->createQueryBuilder();
        $qb->select('c');
        $qb->from(Category::class, 'c');
        $qb->join('c.lastRevision', 'r');
        $qb->where($qb->expr()->eq('r.name', ':name'));
        $qb->setParameter('name', $name);

        return $qb->getQuery()->getOneOrNullResult() !== null;
    }

    private function hasExistingSlug(string $slug): bool
    {
        $qb = $this->entityManager->createQueryBuilder();
        $qb->select('c');
        $qb->from(Category::class, 'c');
        $qb->join('c.lastRevision', 'r');
        $qb->where($qb->expr()->eq('r.slug', ':slug'));
        $qb->setParameter('slug', $slug);

        return $qb->getQuery()->getOneOrNullResult() !== null;
    }
}
