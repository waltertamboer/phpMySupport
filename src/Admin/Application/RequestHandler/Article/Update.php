<?php

declare(strict_types=1);

namespace Support\Admin\Application\RequestHandler\Article;

use Doctrine\ORM\EntityManagerInterface;
use Laminas\Diactoros\Response\HtmlResponse;
use Laminas\Diactoros\Response\RedirectResponse;
use Mezzio\Authentication\UserInterface;
use Mezzio\Template\TemplateRendererInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Ramsey\Uuid\Uuid;
use Support\KnowledgeBase\Domain\Article\Article;
use Support\KnowledgeBase\Domain\Category\Category;
use Support\System\Application\Exception\ResourceNotFound;
use Support\System\Domain\I18n\LocaleQueryRepository;
use Support\System\Domain\I18n\UsedLocale;
use Support\System\Domain\SettingManager;

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
        $entity = $this->entityManager->find(Article::class, $entityId);

        if ($entity === null) {
            throw ResourceNotFound::fromRequest($request);
        }

        $categories = $this->loadCategories();
        $locale = $entity->getLastRevision()->getLocale();

        $formData = [
            'title' => $entity->getLastRevision()->getTitle(),
            'slug' => $entity->getLastRevision()->getSlug(),
            'locale' => $locale === null ? null : [
                'id' => $locale->getId()->toString(),
                'name' => $locale->getName(),
            ],
            'categories' => array_map(static function (Category $category): string {
                return $category->getId()->toString();
            }, $entity->getLastRevision()->getCategories()),
            'body' => $entity->getLastRevision()->getBody(),
        ];

        if (count($formData['categories']) === 0) {
            $formData['categories'] = [ '' ];
        }

        $error = false;
        $errorMsg = null;

        if ($request->getMethod() === 'POST') {
            $formData = $request->getParsedBody();

            $selectedCategories = array_filter((array)($formData['categories'] ?? []));
            $formData['categories'] = $selectedCategories;

            $formTitle = $formData['title'] ?? '';
            $formSlug = $formData['slug'] ?? '';
            $formBody = $formData['body'] ?? '';

            $formLocale = $this->loadLocale($formData['locale']);
            $formData['locale'] = $formLocale === null ? null : [
                'id' => $formLocale->getId()->toString(),
                'name' => $formLocale->getName(),
            ];

            if ($formTitle === '') {
                $error = true;
                $errorMsg = 'No title provided.';
            } elseif ($formTitle !== $entity->getLastRevision()->getTitle() && $this->hasExistingTitle($formTitle)) {
                $error = true;
                $errorMsg = 'The article already exists.';
            } elseif ($formSlug === '') {
                $error = true;
                $errorMsg = 'No slug provided.';
            } elseif ($formSlug !== $entity->getLastRevision()->getSlug() && $this->hasExistingSlug($formSlug)) {
                $error = true;
                $errorMsg = 'The slug already exists.';
            } elseif ($formLocale === null) {
                $error = true;
                $errorMsg = 'No locale provided.';
            } elseif ($formBody === '') {
                $error = true;
                $errorMsg = 'No body provided.';
            } else {
                $entityRevision = $entity->createRevision(
                    $user,
                    $formLocale,
                    $formTitle,
                    $formSlug,
                    $formBody
                );

                foreach ($selectedCategories as $category) {
                    $category = $this->entityManager->find(Category::class, $category);

                    if ($category !== null) {
                        $entityRevision->addCategory($category);
                    }
                }

                $this->entityManager->flush();

                return new RedirectResponse('/admin/articles');
            }
        }

        return new HtmlResponse($this->renderer->render(
            '@admin/article/update.html.twig',
            [
                'entity' => $entity,
                'categories' => $categories,
                'formData' => $formData,
                'error' => $error,
                'errorMsg' => $errorMsg,
            ],
        ));
    }

    private function loadLocale(?string $id): ?UsedLocale
    {
        if ($id === null || $id === '' || !Uuid::isValid($id)) {
            return null;
        }

        return $this->entityManager->find(UsedLocale::class, $id);
    }

    /**
     * @return Category[]
     */
    private function loadCategories(): array
    {
        $qb = $this->entityManager->createQueryBuilder();
        $qb->select('c');
        $qb->from(Category::class, 'c');
        $qb->join('c.lastRevision', 'r');
        $qb->orderBy($qb->expr()->asc('r.name'));

        return $qb->getQuery()->getResult();
    }

    private function hasExistingTitle(string $title): bool
    {
        $qb = $this->entityManager->createQueryBuilder();
        $qb->select('a');
        $qb->from(Article::class, 'a');
        $qb->join('a.lastRevision', 'r');
        $qb->where($qb->expr()->eq('r.title', ':title'));
        $qb->setParameter('title', $title);

        return $qb->getQuery()->getOneOrNullResult() !== null;
    }

    private function hasExistingSlug(string $slug): bool
    {
        $qb = $this->entityManager->createQueryBuilder();
        $qb->select('a');
        $qb->from(Article::class, 'a');
        $qb->join('a.lastRevision', 'r');
        $qb->where($qb->expr()->eq('r.slug', ':slug'));
        $qb->setParameter('slug', $slug);

        return $qb->getQuery()->getOneOrNullResult() !== null;
    }
}
