<?php

declare(strict_types=1);

namespace Support\System\Domain;

final class ApplicationConfig
{
    /** @var array<string, string> */
    private array $config;

    /**
     * @param array<string, string> $config
     */
    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function getSiteTheme(): string
    {
        return $this->config['siteTheme'] ?? 'default';
    }

    public function getSiteThemeAdmin(): string
    {
        return $this->config['siteThemeAdmin'] ?? 'admin';
    }

    public function getSiteTitle(): string
    {
        return $this->config['siteTitle'] ?? '';
    }

    public function getSiteDescription(): ?string
    {
        return $this->config['siteDescription'] ?? null;
    }

    public function getSiteBasePath(): string
    {
        return $this->config['siteBasePath'] ?? '/';
    }

    public function getSiteHomepage(): ?string
    {
        return $this->config['siteHomepage'] ?? null;
    }

    public function isSearchEnabled(): bool
    {
        return ($this->config['siteSearchEnabled'] ?? true) === true;
    }

    public function getTinyMceApiKey(): string
    {
        return $this->config['tinyMceApiKey'] ?? '';
    }

    public function getTinyMceConfig(): array
    {
        return $this->config['tinyMceConfig'] ?? [];
    }
}
