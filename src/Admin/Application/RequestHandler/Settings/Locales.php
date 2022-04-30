<?php

declare(strict_types=1);

namespace Support\Admin\Application\RequestHandler\Settings;

use Doctrine\ORM\EntityManagerInterface;
use Laminas\Diactoros\Response\HtmlResponse;
use Mezzio\Template\TemplateRendererInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Support\System\Domain\I18n\UsedLocale;

final class Locales implements RequestHandlerInterface
{
    public function __construct(
        private readonly TemplateRendererInterface $renderer,
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return new HtmlResponse($this->renderer->render(
            '@admin/settings/locales/overview.html.twig',
            [
                'locales' => $this->loadLocales(),
            ],
        ));
    }

    private function loadLocales(): array
    {
        $qb = $this->entityManager->createQueryBuilder();
        $qb->select('l');
        $qb->from(UsedLocale::class, 'l');
        $qb->orderBy($qb->expr()->asc('l.name'));

        return $qb->getQuery()->getResult();
    }
}
