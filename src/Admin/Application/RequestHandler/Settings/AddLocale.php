<?php

declare(strict_types=1);

namespace Support\Admin\Application\RequestHandler\Settings;

use Doctrine\ORM\EntityManagerInterface;
use Laminas\Diactoros\Response\HtmlResponse;
use Laminas\Diactoros\Response\RedirectResponse;
use Mezzio\Template\TemplateRendererInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Support\KnowledgeBase\Domain\Category\Category;
use Support\KnowledgeBase\Domain\Media\File;
use Support\System\Domain\I18n\UsedLocale;
use Support\System\Domain\Locale;

final class AddLocale implements RequestHandlerInterface
{
    public function __construct(
        private readonly TemplateRendererInterface $renderer,
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $formData = [
            'name' => '',
            'slug' => '',
            'selectorText' => '',
            'thumbnail' => null,
            'enabled' => true,
        ];

        $error = false;
        $errorMsg = null;

        if ($request->getMethod() === 'POST') {
            $formData = $request->getParsedBody();

            $formName = $formData['name'] ?? '';
            $formSlug = $formData['slug'] ?? '';

            if ($formName === '') {
                $error = true;
                $errorMsg = 'No name provided.';
            } elseif ($this->hasExistingName($formName)) {
                $error = true;
                $errorMsg = 'The category already exists.';
            } elseif ($formSlug === '') {
                $error = true;
                $errorMsg = 'No slug provided.';
            } elseif ($this->hasExistingSlug($formSlug)) {
                $error = true;
                $errorMsg = 'The slug already exists.';
            } else {
                $locale = new UsedLocale(
                    $formData['name'],
                    $formData['slug'],
                    ($formData['enabled'] ?? '') === '1',
                );

                $locale->setSelectorText($formData['selectorText'] ?? null);
                $locale->setThumbnail($this->loadThumbnail($formData['thumbnail']));

                $this->entityManager->persist($locale);
                $this->entityManager->flush();

                return new RedirectResponse('/admin/settings/locales');
            }
        }

        return new HtmlResponse($this->renderer->render(
            '@admin/settings/locales/add.html.twig',
            [
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
