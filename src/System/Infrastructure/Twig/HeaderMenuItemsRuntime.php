<?php

declare(strict_types=1);

namespace Support\System\Infrastructure\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

final class HeaderMenuItemsRuntime extends AbstractExtension
{
    /** @var array<string, mixed> */
    private array $config;

    /**
     * @param array<string, mixed> $config
     */
    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function getFunctions()
    {
        $config = $this->config;

        return [
            new TwigFunction('siteTitle', static function () use ($config): string {
                return $config['siteTitle'] ?? '';
            }),
            new TwigFunction('siteLink', static function () use ($config): string {
                return $config['siteLink'] ?? '';
            }),
            new TwigFunction('enableSearch', static function () use ($config): bool {
                return $config['enableSearch'] === true ?? true;
            }),
            new TwigFunction('headerLeftMenuItems', static function () use ($config): array {
                return (array)$config['leftMenuItems'];
            }),
            new TwigFunction('headerRightMenuItems', static function () use ($config): array {
                return (array)$config['rightMenuItems'];
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
