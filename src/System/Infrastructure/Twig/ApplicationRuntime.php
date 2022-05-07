<?php

declare(strict_types=1);

namespace Support\System\Infrastructure\Twig;

use Support\System\Domain\I18n\UsedLocaleList;
use Support\System\Domain\I18n\UsedLocaleRepository;
use Support\System\Domain\SettingManager;
use Twig\Extension\AbstractExtension;
use Twig\Extension\GlobalsInterface;
use Twig\TwigFilter;
use Twig\TwigFunction;

final class ApplicationRuntime extends AbstractExtension implements GlobalsInterface
{
    public function __construct(
        private readonly SettingManager $settingManager,
        private readonly UsedLocaleRepository $usedLocalesRepository,
    ) {
    }

    public function getFunctions() // phpcs:ignore
    {
        return [
            new TwigFunction('siteTitle', function (): string {
                return $this->settingManager->get('title');
            }),
            new TwigFunction('siteBasePath', function (): string {
                return $this->settingManager->get('basePath');
            }),
            new TwigFunction('siteHomepage', function (): string {
                return $this->settingManager->get('homepage');
            }),
            new TwigFunction('searchEnabled', function (): bool {
                return $this->settingManager->get('searchEnabled') === '1';
            }),
            new TwigFunction('ticketsEnabled', function (): bool {
                return $this->settingManager->get('ticketsEnabled') === '1';
            }),
            new TwigFunction('googleTranslateEnabled', function (): bool {
                return $this->settingManager->get('googleTranslateEnabled') === '1';
            }),
            new TwigFunction('localizationEnabled', function (): bool {
                return $this->settingManager->get('localizationEnabled') === '1';
            }),
            new TwigFunction('tinyMceApiKey', function (): string {
                return $this->settingManager->get('tinyMceApiKey');
            }),
            new TwigFunction('tinyMceConfig', function (): array {
                return json_decode(
                    $this->settingManager->get('tinyMceConfig'), 
                    true
                );
            }),
            new TwigFunction('sortQuery', function (string $field, string $currentValue): string {
                $ascending = str_starts_with($currentValue, '+');
                $currentField = substr($currentValue, 1);

                if ($currentField === $field) {
                    return $ascending ? '-' . $field : '+' . $field;
                }

                return '+' . $field;
            }),
            new TwigFunction('locales', function (): UsedLocaleList {
                $locales = $this->usedLocalesRepository->getUsedLocales();

                return $locales;
            }),
        ];
    }

    public function getFilters() // phpcs:ignore
    {
        return [
            new TwigFilter('formatBytes', function (int $bytes, bool $si = true): string {
                $unit = $si ? 1000 : 1024;

                if ($bytes <= $unit) {
                    return $bytes . " B";
                }

                $exp = intval((log($bytes) / log($unit)));
                $pre = ($si ? "kMGTPE" : "KMGTPE");
                $pre = $pre[$exp - 1] . ($si ? "" : "i");

                return sprintf("%.1f %sB", $bytes / pow($unit, $exp), $pre);
            }),
        ];
    }

    public function getGlobals(): array
    {
        $config = $this->settingManager;

        return [
            'locale' => $config->get('defaultLocale', 'en_US'),
        ];
    }
}
