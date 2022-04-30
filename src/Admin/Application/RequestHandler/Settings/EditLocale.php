<?php

declare(strict_types=1);

namespace Support\Admin\Application\RequestHandler\Settings;

use Doctrine\ORM\EntityManagerInterface;
use Laminas\Diactoros\Response\HtmlResponse;
use Laminas\Diactoros\Response\RedirectResponse;
use Mezzio\Authentication\UserInterface;
use Mezzio\Template\TemplateRendererInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Support\KnowledgeBase\Domain\Media\File;
use Support\System\Application\Exception\ResourceNotFound;
use Support\System\Domain\I18n\UsedLocale;

final class EditLocale implements RequestHandlerInterface
{
    public function __construct(
        private readonly TemplateRendererInterface $renderer,
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $user = $request->getAttribute(UserInterface::class);

        if ($user === null || !$user->isAdmin()) {
            throw ResourceNotFound::fromRequest($request);
        }

        $entityId = $request->getAttribute('id');
        $entity = $this->entityManager->find(UsedLocale::class, $entityId);

        if ($entity === null) {
            throw ResourceNotFound::fromRequest($request);
        }

        $thumbnail = null;
        $entityThumbnail = $entity->getThumbnail();
        if ($entityThumbnail !== null) {
            $thumbnail = [
                'id' => $entityThumbnail->getId()->toString(),
                'name' => $entityThumbnail->getLastRevision()->getName(),
            ];
        }

        $formData = [
            'name' => $entity->getName(),
            'slug' => $entity->getSlug(),
            'selectorText' => $entity->getSelectorText(),
            'thumbnail' => $thumbnail,
            'enabled' => $entity->getEnabled(),
        ];

        $error = false;
        $errorMsg = null;

        if ($request->getMethod() === 'POST') {
            $formData = $request->getParsedBody();

            $formName = $formData['name'] ?? '';
            $formSlug = $formData['slug'] ?? '';
            $formThumbnail = null;

            if ($formData['thumbnail'] !== null) {
                $formThumbnail = $this->loadThumbnail($formData['thumbnail']);

                if ($formThumbnail !== null) {
                    $formData['thumbnail'] = [
                        'id' => $formThumbnail->getId()->toString(),
                        'name' => $formThumbnail->getLastRevision()->getName(),
                    ];
                }
            }

            if ($formName === '') {
                $error = true;
                $errorMsg = 'No name provided.';
            } elseif ($entity->getName() !== $formName && $this->hasExistingName($formName)) {
                $error = true;
                $errorMsg = 'The locale with this name already exists.';
            } elseif ($formSlug === '') {
                $error = true;
                $errorMsg = 'No slug provided.';
            } elseif ($entity->getSlug() !== $formSlug && $this->hasExistingSlug($formSlug)) {
                $error = true;
                $errorMsg = 'The slug already exists.';
            } else {
                $entity->setName($formName);
                $entity->setSlug($formSlug);
                $entity->setSelectorText($formData['selectorText'] ?? null);
                $entity->setThumbnail($formThumbnail);

                $this->entityManager->flush();

                return new RedirectResponse('/admin/settings/locales');
            }
        }

        return new HtmlResponse($this->renderer->render(
            '@admin/settings/locales/update.html.twig',
            [
                'entity' => $entity,
                'formData' => $formData,
                'error' => $error,
                'errorMsg' => $errorMsg,
            ],
        ));
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
        $qb->select('l');
        $qb->from(UsedLocale::class, 'l');
        $qb->where($qb->expr()->eq('l.name', ':name'));
        $qb->setParameter('name', $name);

        return $qb->getQuery()->getOneOrNullResult() !== null;
    }

    private function hasExistingSlug(string $slug): bool
    {
        $qb = $this->entityManager->createQueryBuilder();
        $qb->select('l');
        $qb->from(UsedLocale::class, 'l');
        $qb->where($qb->expr()->eq('l.slug', ':slug'));
        $qb->setParameter('slug', $slug);

        return $qb->getQuery()->getOneOrNullResult() !== null;
    }
}
