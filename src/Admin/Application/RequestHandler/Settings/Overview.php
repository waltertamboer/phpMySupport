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
use Support\Admin\Domain\Account\User;
use Support\System\Application\Exception\ResourceNotFound;
use Support\System\Domain\I18n\LocaleRepository;
use Support\System\Domain\Setting;

final class Overview implements RequestHandlerInterface
{
    public function __construct(
        private readonly TemplateRendererInterface $renderer,
        private readonly EntityManagerInterface $entityManager,
        private readonly LocaleRepository $localeRepository,
    ) {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $currentUser = $request->getAttribute(UserInterface::class);

        if ($currentUser === null || !$currentUser->isOwner()) {
            throw ResourceNotFound::fromRequest($request);
        }

        $qb = $this->entityManager->createQueryBuilder();
        $qb->select('s');
        $qb->from(Setting::class, 's');

        $settingRowset = $qb->getQuery()->getResult();

        $settings = [];
        $formData = [];
        foreach ($settingRowset as $setting) {
            assert($setting instanceof Setting);

            $settings[$setting->getName()] = $setting;
            $formData[$setting->getName()] = $setting->getValue();
        }

        if (array_key_exists('defaultLocale', $formData)) {
            $defaultLocale = $this->localeRepository->lookup($formData['defaultLocale']);

            $formData['defaultLocaleValue'] = '';
            if ($defaultLocale !== null) {
                $formData['defaultLocaleValue'] = $defaultLocale->getName();
            }
        }

        $error = false;
        $errorMsg = null;

        if ($request->getMethod() === 'POST') {
            $formData = $request->getParsedBody();

            $checkboxes = [
                'searchEnabled',
                'googleTranslateEnabled',
            ];

            foreach ($formData as $formName => $formValue) {
                if (!array_key_exists($formName, $settings)) {
                    continue;
                }

                $settings[$formName]->setValue($formValue);
            }

            foreach ($checkboxes as $formName) {
                if (!array_key_exists($formName, $formData)) {
                    $settings[$formName]->setValue('0');
                }
            }

            $this->entityManager->flush();

            return new RedirectResponse('/admin/settings');
        }

        return new HtmlResponse($this->renderer->render(
            '@admin/settings/overview.html.twig',
            [
                'formData' => $formData,
                'error' => $error,
                'errorMsg' => $errorMsg,
            ],
        ));
    }
}
