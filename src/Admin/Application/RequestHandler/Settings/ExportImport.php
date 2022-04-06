<?php

declare(strict_types=1);

namespace Support\Admin\Application\RequestHandler\Settings;

use DateTimeInterface;
use Doctrine\ORM\EntityManagerInterface;
use DOMDocument;
use DOMElement;
use Laminas\Diactoros\Response\HtmlResponse;
use Laminas\Diactoros\Response\JsonResponse;
use Laminas\Diactoros\Response\XmlResponse;
use Mezzio\Authentication\UserInterface;
use Mezzio\Template\TemplateRendererInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Ramsey\Uuid\UuidInterface;
use Support\Admin\Domain\Account\User;
use Support\KnowledgeBase\Domain\Article\Article;
use Support\KnowledgeBase\Domain\Article\ArticleRevision;
use Support\KnowledgeBase\Domain\Category\Category;
use Support\KnowledgeBase\Domain\Category\CategoryRevision;
use Support\KnowledgeBase\Domain\Media\File;
use Support\KnowledgeBase\Domain\Media\FileRevision;
use Support\System\Application\Exception\ResourceNotFound;

final class ExportImport implements RequestHandlerInterface
{
    public function __construct(
        private readonly TemplateRendererInterface $renderer,
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $currentUser = $request->getAttribute(UserInterface::class);

        if ($currentUser === null || !$currentUser->isOwner()) {
            throw ResourceNotFound::fromRequest($request);
        }

        if ($request->getMethod() === 'POST') {
            $formData = $request->getParsedBody();

            if (array_key_exists('export', $formData)) {
                return $this->handleExport($request);
            }

            return $this->handleImport($request);
        }

        return new HtmlResponse($this->renderer->render(
            '@admin/settings/export-import.html.twig',
            [
            ],
        ));
    }

    private function handleExport(ServerRequestInterface $request): ResponseInterface
    {
        $domDocument = new DOMDocument();

        $rootElement = $domDocument->createElement('phpmysupport');
        $domDocument->appendChild($rootElement);

        $this->exportArticles($rootElement);
        $this->exportCategories($rootElement);
        $this->exportMedia($rootElement);
        $this->exportUsers($rootElement);

        $response = new XmlResponse($domDocument->saveXML());

        $response = $response
            ->withAddedHeader('Cache-Control', 'no-cache, must-revalidate')
            ->withAddedHeader('Content-Description', 'File Transfer')
            ->withAddedHeader('Content-Disposition', 'attachment; filename="phpsupport.xml"')
            ->withAddedHeader('Content-Type', 'application/xml')
            ->withAddedHeader('Expires', '0')
            ->withAddedHeader('Pragma', 'public');

        return $response;
    }

    private function handleImport(ServerRequestInterface $request): ResponseInterface
    {
        return new JsonResponse([]);
    }

    private function exportUsers(DOMElement $parentElement): void
    {
        $usersElement = $this->xmlAppend($parentElement, 'users');

        $qb = $this->entityManager->createQueryBuilder();
        $qb->select('e');
        $qb->from(User::class, 'e');

        $users = $qb->getQuery()->getResult();

        foreach ($users as $user) {
            assert($user instanceof User);

            $userElement = $this->xmlAppend($usersElement, 'user');

            $this->xmlAppendStringValue($userElement, 'id', $user->getId()->toString());
            $this->xmlAppendStringValue($userElement, 'username', $user->getUsername());
            $this->xmlAppendStringValue($userElement, 'password', $user->getPassword());
            $this->xmlAppendStringValue($userElement, 'role', $user->getRole());
            $this->xmlAppendBooleanValue($userElement, 'enabled', true);
        }
    }

    private function exportMedia(DOMElement $parentElement): void
    {
        $qb = $this->entityManager->createQueryBuilder();
        $qb->select('e');
        $qb->from(File::class, 'e');

        $mediaFiles = $qb->getQuery()->getResult();

        $mediaElement = $this->xmlAppend($parentElement, 'media');

        foreach ($mediaFiles as $file) {
            assert($file instanceof File);

            $fileElement = $this->xmlAppend($mediaElement, 'file');

            $this->xmlAppendUuidValue($fileElement, 'id', $file->getId());
            $this->xmlAppendDateTimeValue($fileElement, 'createdAt', $file->getCreatedAt());
            $this->xmlAppendUserValue($fileElement, 'createdBy', $file->getCreatedBy());
            $this->xmlAppendUuidValue($fileElement, 'lastRevisionid', $file->getLastRevision()->getId());

            $revisionsElement = $this->xmlAppend($fileElement, 'revisions');

            foreach ($file->getRevisions() as $revision) {
                assert($revision instanceof FileRevision);

                $data = null;

                $targetPath = $revision->getTargetPath();
                if (is_file($targetPath)) {
                    $data = base64_encode(file_get_contents($targetPath));
                }

                $revisionElement = $this->xmlAppend($revisionsElement, 'revision');
                $this->xmlAppendUuidValue($revisionElement, 'id', $revision->getId());
                $this->xmlAppendDateTimeValue($revisionElement, 'createdAt', $revision->getCreatedAt());
                $this->xmlAppendUserValue($revisionElement, 'createdBy', $revision->getCreatedBy());
                $this->xmlAppendStringValue($revisionElement, 'name', $revision->getName());
                $this->xmlAppendStringValue($revisionElement, 'mimeType', $revision->getMimeType());
                $this->xmlAppendStringValue($revisionElement, 'size', (string)$revision->getSize());
                $this->xmlAppendStringValue($revisionElement, 'data', $data);
            }
        }
    }

    private function exportCategories(DOMElement $parentElement): void
    {
        $qb = $this->entityManager->createQueryBuilder();
        $qb->select('e');
        $qb->from(Category::class, 'e');

        $categories = $qb->getQuery()->getResult();

        $categoriesElement = $this->xmlAppend($parentElement, 'categories');

        foreach ($categories as $category) {
            assert($category instanceof Category);

            $categoryElement = $this->xmlAppend($categoriesElement, 'category');

            $this->xmlAppendUuidValue($categoryElement, 'id', $category->getId());
            $this->xmlAppendDateTimeValue($categoryElement, 'createdAt', $category->getCreatedAt());
            $this->xmlAppendUserValue($categoryElement, 'createdBy', $category->getCreatedBy());
            $this->xmlAppendUuidValue($categoryElement, 'lastRevisionid', $category->getLastRevision()->getId());

            $revisionsElement = $this->xmlAppend($categoryElement, 'revisions');

            foreach ($category->getRevisions() as $revision) {
                assert($revision instanceof CategoryRevision);

                $revisionElement = $this->xmlAppend($revisionsElement, 'revision');
                $this->xmlAppendUuidValue($revisionElement, 'id', $revision->getId());
                $this->xmlAppendDateTimeValue($revisionElement, 'createdAt', $revision->getCreatedAt());
                $this->xmlAppendUserValue($revisionElement, 'createdBy', $revision->getCreatedBy());
                $this->xmlAppendStringValue($revisionElement, 'name', $revision->getName());
                $this->xmlAppendStringValue($revisionElement, 'slug', $revision->getSlug());
                $this->xmlAppendMediaFileValue($revisionElement, 'thumbnail', $revision->getThumbnail());
            }
        }
    }

    private function exportArticles(DOMElement $parentElement): void
    {
        $qb = $this->entityManager->createQueryBuilder();
        $qb->select('e');
        $qb->from(Article::class, 'e');

        $articles = $qb->getQuery()->getResult();

        $articlesElement = $this->xmlAppend($parentElement, 'articles');

        foreach ($articles as $article) {
            assert($article instanceof Article);

            $firstRevision = null;
            foreach ($article->getRevisions() as $revision) {
                assert($revision instanceof ArticleRevision);

                if ($firstRevision === null ||
                    $revision->getCreatedAt()->getTimestamp() < $firstRevision->getCreatedAt()->getTimestamp()) {
                    $firstRevision = $revision;
                }
            }

            $articleElement = $this->xmlAppend($articlesElement, 'article');

            $this->xmlAppendUuidValue($articleElement, 'id', $article->getId());
            $this->xmlAppendDateTimeValue($articleElement, 'createdAt', $article->getCreatedAt());
            $this->xmlAppendUserValue($articleElement, 'createdBy', $firstRevision->getCreatedBy());
            $this->xmlAppendUuidValue($articleElement, 'lastRevisionid', $article->getLastRevision()->getId());

            $revisionsElement = $this->xmlAppend($articleElement, 'revisions');

            foreach ($article->getRevisions() as $revision) {
                assert($revision instanceof ArticleRevision);

                $revisionElement = $this->xmlAppend($revisionsElement, 'revision');
                $this->xmlAppendUuidValue($revisionElement, 'id', $revision->getId());
                $this->xmlAppendDateTimeValue($revisionElement, 'createdAt', $revision->getCreatedAt());
                $this->xmlAppendUserValue($revisionElement, 'createdBy', $revision->getCreatedBy());
                $this->xmlAppendStringValue($revisionElement, 'title', $revision->getTitle());
                $this->xmlAppendStringValue($revisionElement, 'slug', $revision->getSlug());
                $this->xmlAppendStringValue($revisionElement, 'body', $revision->getBody());
            }
        }
    }

    private function xmlAppend(DOMElement $parentElement, string $name): DOMElement
    {
        $dom = $parentElement->ownerDocument;

        $element = $dom->createElement($name);

        $parentElement->appendChild($element);

        return $element;
    }

    private function xmlAppendBooleanValue(DOMElement $parentElement, string $name, bool $value): DOMElement
    {
        $dom = $parentElement->ownerDocument;

        $element = $dom->createElement($name);
        $element->nodeValue = $value ? 'true' : 'false';

        $parentElement->appendChild($element);

        return $element;
    }

    private function xmlAppendDateTimeValue(DOMElement $parentElement, string $name, DateTimeInterface $value): DOMElement
    {
        $dom = $parentElement->ownerDocument;

        $element = $dom->createElement($name);
        $element->nodeValue = (string)$value->getTimestamp();

        $parentElement->appendChild($element);

        return $element;
    }

    private function xmlAppendMediaFileValue(DOMElement $parentElement, string $name, ?File $file): DOMElement
    {
        $dom = $parentElement->ownerDocument;

        $element = $dom->createElement($name);

        if ($file !== null) {
            $element->nodeValue = $file->getId()->toString();
        }

        $parentElement->appendChild($element);

        return $element;
    }

    private function xmlAppendStringValue(DOMElement $parentElement, string $name, string $value): DOMElement
    {
        $dom = $parentElement->ownerDocument;

        $element = $dom->createElement($name);
        $element->appendChild($dom->createCDATASection($value));

        $parentElement->appendChild($element);

        return $element;
    }

    private function xmlAppendUserValue(DOMElement $parentElement, string $name, User $value): DOMElement
    {
        return $this->xmlAppendUuidValue($parentElement, $name, $value->getId());
    }

    private function xmlAppendUuidValue(DOMElement $parentElement, string $name, UuidInterface $value): DOMElement
    {
        $dom = $parentElement->ownerDocument;

        $element = $dom->createElement($name);
        $element->nodeValue = $value->toString();

        $parentElement->appendChild($element);

        return $element;
    }
}
