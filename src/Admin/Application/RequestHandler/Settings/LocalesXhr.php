<?php

declare(strict_types=1);

namespace Support\Admin\Application\RequestHandler\Settings;

use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Support\System\Domain\I18n\Locale;
use Support\System\Domain\I18n\LocaleQueryRepository;

final class LocalesXhr implements RequestHandlerInterface
{
    public function __construct(
        private readonly LocaleQueryRepository $LocaleQueryRepository,
    ) {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $queryParams = $request->getQueryParams();
        $query = $queryParams['q'] ?? '';

        $localeList = $this->LocaleQueryRepository->query($query);

        return new JsonResponse(array_values(array_map(static function (Locale $locale): array {
            return [
                'id' => $locale->getId(),
                'name' => $locale->getName(),
            ];
        }, $localeList->toArray())));
    }
}
