<?php

declare(strict_types=1);

namespace Support\System\Infrastructure\Twig;

use Support\System\Domain\ApplicationConfig;
use Support\System\Domain\I18n\LocaleList;
use Support\System\Domain\I18n\LocaleRepository;
use Support\System\Domain\SettingManager;
use Twig\Extension\AbstractExtension;
use Twig\Extension\GlobalsInterface;
use Twig\TwigFilter;
use Twig\TwigFunction;

final class ApplicationRuntime extends AbstractExtension implements GlobalsInterface
{
    public function __construct(
        private readonly SettingManager $settingManager,
        private readonly LocaleRepository $localeRepository,
    ) {
    }

    public function getFunctions()
    {
        $config = $this->settingManager;

        return [
            new TwigFunction('siteTitle', static function () use ($config): string {
                return $config->get('title');
            }),
            new TwigFunction('siteBasePath', static function () use ($config): string {
                return $config->get('basePath');
            }),
            new TwigFunction('siteHomepage', static function () use ($config): string {
                return $config->get('homepage');
            }),
            new TwigFunction('searchEnabled', static function () use ($config): bool {
                return $config->get('searchEnabled') === '1';
            }),
            new TwigFunction('googleTranslateEnabled', static function () use ($config): bool {
                return $config->get('googleTranslateEnabled') === '1';
            }),
            new TwigFunction('localizationEnabled', static function () use ($config): bool {
                return $config->get('localizationEnabled') === '1';
            }),
            new TwigFunction('tinyMceApiKey', static function () use ($config): string {
                return $config->get('tinyMceApiKey');
            }),
            new TwigFunction('tinyMceConfig', static function () use ($config): array {
                return json_decode($config->get('tinyMceConfig'), true);
            }),
            new TwigFunction('sortQuery', static function (string $field, string $currentValue): string {
                $ascending = str_starts_with($currentValue, '+');
                $currentField = substr($currentValue, 1);

                if ($currentField === $field) {
                    return $ascending ? '-' . $field : '+' . $field;
                }

                return '+' . $field;
            }),
            new TwigFunction('locales', function (): LocaleList {
                $locales = $this->localeRepository->getUsedLocales();

                return $locales;
            }),
        ];
    }

    public function getFilters()
    {
        return [
            new TwigFilter('formatBytes', static function (int $bytes, bool $si = true): string {
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
