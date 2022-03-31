<?php

declare(strict_types=1);

namespace Support\System\Infrastructure\Twig;

use Support\System\Domain\ApplicationConfig;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

final class ApplicationRuntime extends AbstractExtension
{
    public function __construct(
        private readonly ApplicationConfig $config
    ) {
    }

    public function getFunctions()
    {
        $config = $this->config;

        return [
            new TwigFunction('siteTitle', static function () use ($config): string {
                return $config->getSiteTitle();
            }),
            new TwigFunction('siteDescription', static function () use ($config): string {
                return $config->getSiteDescription();
            }),
            new TwigFunction('siteBasePath', static function () use ($config): string {
                return $config->getSiteBasePath();
            }),
            new TwigFunction('siteHomepage', static function () use ($config): string {
                return $config->getSiteHomepage();
            }),
            new TwigFunction('siteSearchEnabled', static function () use ($config): bool {
                return $config->isSearchEnabled();
            }),
            new TwigFunction('tinyMceApiKey', static function () use ($config): string {
                return $config->getTinyMceApiKey();
            }),
            new TwigFunction('tinyMceConfig', static function () use ($config): array {
                return $config->getTinyMceConfig();
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
}
