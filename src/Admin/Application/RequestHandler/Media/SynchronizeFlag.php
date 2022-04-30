<?php

declare(strict_types=1);

namespace Support\Admin\Application\RequestHandler\Media;

use Doctrine\ORM\EntityManagerInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\Psr7\Utils;
use Laminas\Diactoros\Response\HtmlResponse;
use Laminas\Diactoros\Response\JsonResponse;
use Laminas\Diactoros\Response\RedirectResponse;
use Laminas\Diactoros\UploadedFile;
use Mezzio\Authentication\UserInterface;
use Mezzio\Template\TemplateRendererInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Support\Admin\Domain\Media\MediaUploadProcessor;
use Support\KnowledgeBase\Domain\Category\Category;
use Support\KnowledgeBase\Domain\Category\CategoryName;
use Support\KnowledgeBase\Domain\Category\CategorySlug;
use Support\KnowledgeBase\Domain\Media\File;
use Support\System\Application\Exception\ResourceNotFound;
use Support\System\Domain\I18n\UsedLocale;

final class SynchronizeFlag implements RequestHandlerInterface
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

        $localeId = $request->getAttribute('localeId');
        $locale = $this->entityManager->getRepository(UsedLocale::class)->find($localeId);
        assert($locale === null || $locale instanceof UsedLocale);

        if ($locale === null) {
            throw ResourceNotFound::fromRequest($request);
        }

        $flagId = $locale->getCountry();
        $localFileName = sprintf('flag-%s.svg', $flagId);

        $qb = $this->entityManager->createQueryBuilder();
        $qb->select('f');
        $qb->from(File::class, 'f');
        $qb->join('f.lastRevision', 'r');
        $qb->where($qb->expr()->eq('r.name', ':name'));
        $qb->setParameter('name', $localFileName);

        $result = $qb->getQuery()->getOneOrNullResult();

        if ($result !== null) {
            $locale->setThumbnail($result);
            $this->entityManager->flush();

            return new RedirectResponse('/admin/settings/locales');
        }

        $flagUrl = sprintf(
            'https://flagicons.lipis.dev/flags/4x3/%s.svg',
            $flagId
        );

        try {
            $client = new Client();
            $response = $client->get(Utils::uriFor($flagUrl));
        } catch (ClientException $exception) {
            return new RedirectResponse('/admin/settings/locales');
        } catch (ServerException $exception) {
            return new RedirectResponse('/admin/settings/locales');
        }

        $uploadedFile = new UploadedFile(
            $response->getBody(),
            0,
            UPLOAD_ERR_OK,
            $localFileName,
            'image/svg+xml'
        );

        $processor = new MediaUploadProcessor($this->entityManager);
        $file = $processor($user, $uploadedFile);

        $locale->setThumbnail($file);
        $this->entityManager->flush();

        return new RedirectResponse('/admin/settings/locales');
    }
}
