<?php

declare(strict_types=1);

namespace Support\System\Infrastructure\Laminas\ConfigAggregator;

final class EnvironmentProvider
{
    public function __invoke()
    {
        $dbConnection = [];
        $this->populateString($dbConnection, 'PMS_DB_TYPE', 'driver');
        $this->populateString($dbConnection, 'PMS_DB_HOST', 'host');
        $this->populateString($dbConnection, 'PMS_DB_PORT', 'port');
        $this->populateString($dbConnection, 'PMS_DB_NAME', 'dbname');
        $this->populateString($dbConnection, 'PMS_DB_USER', 'user');
        $this->populateString($dbConnection, 'PMS_DB_PASS', 'password');

        $phpMySupport = [];
        $this->populateString($phpMySupport, 'PMS_SITE_TITLE', 'siteTitle');
        $this->populateString($phpMySupport, 'PMS_SITE_DESCRIPTION', 'siteDescription');
        $this->populateString($phpMySupport, 'PMS_SITE_THEME', 'siteTheme');
        $this->populateString($phpMySupport, 'PMS_SITE_THEME_ADMIN', 'siteThemeAdmin');
        $this->populateString($phpMySupport, 'PMS_SITE_BASE_PATH', 'siteBasePath');
        $this->populateString($phpMySupport, 'PMS_SITE_HOMEPAGE', 'siteHomepage');
        $this->populateString($phpMySupport, 'PMS_SITE_SEARCH_ENABLED', 'siteSearchEnabled');
        $this->populateString($phpMySupport, 'PMS_TINY_MCE_KEY', 'tinyMceApiKey');
        $this->populateJson($phpMySupport, 'PMS_TINY_MCE_CONFIG', 'tinyMceConfig');

        return [
            'doctrine' => [
                'connection' => $dbConnection,
            ],
            'phpMySupport' => $phpMySupport,
        ];
    }

    private function populateString(array &$result, string $envName, string $key): void
    {
        $value = getenv($envName);

        if ($value !== false) {
            $result[$key] = $value;
        }
    }

    private function populateJson(array &$result, string $envName, string $key): void
    {
        $value = getenv($envName);

        if ($value !== false) {
            $result[$key] = $value;
        }
    }
}
