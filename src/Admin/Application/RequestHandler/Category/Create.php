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
use Support\KnowledgeBase\Domain\Article\Article;
use Support\KnowledgeBase\Domain\Category\Category;
use Support\KnowledgeBase\Domain\Category\CategoryName;
use Support\KnowledgeBase\Domain\Category\CategorySlug;
use Support\KnowledgeBase\Domain\Media\File;
use Support\System\Application\Exception\ResourceNotFound;
use Support\System\Domain\I18n\LocaleRepository;
use Support\System\Domain\SettingManager;

final class Create implements RequestHandlerInterface
{
    public function __construct(
        private readonly TemplateRendererInterface $renderer,
        private readonly EntityManagerInterface $entityManager,
        private readonly LocaleRepository $localeRepository,
        private readonly SettingManager $settingManager,
    ) {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $user = $request->getAttribute(UserInterface::class);

        if ($user === null || !$user->isEditor()) {
            throw ResourceNotFound::fromRequest($request);
        }

        $defaultLocale = $this->localeRepository->lookup($this->settingManager->get('defaultLocale', 'en_US'));

        $formData = [
            'name' => '',
            'slug' => '',
            'locale' => $defaultLocale,
            'thumbnail' => null,
        ];

        $error = false;
        $errorMsg = null;

        if ($request->getMethod() === 'POST') {
            $formData = $request->getParsedBody();

            $formLocale = $formData['locale'] ?? 'en';
            $formLocale = $this->localeRepository->lookup($formLocale);
            $formData['locale'] = $formLocale;

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
            } elseif ($formLocale === '') {
                $error = true;
                $errorMsg = 'No locale provided.';
            } else {
                $category = new Category(
                    $user,
                    $formLocale->getId(),
                    $formName,
                    $formSlug,
                );

                $revision = $category->getLastRevision();
                $revision->setThumbnail($this->loadThumbnail($formData['thumbnail']));

                $this->entityManager->persist($category);
                $this->entityManager->flush();

                return new RedirectResponse('/admin/categories');
            }
        }

        return new HtmlResponse($this->renderer->render(
            '@admin/category/create.html.twig',
            [
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
