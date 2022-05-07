<?php

declare(strict_types=1);

namespace Support\System\Domain\I18n;

interface UsedLocaleRepository
{
    public function getUsedLocales(): UsedLocaleList;
    public function find(string $id): ?UsedLocale;
    public function findBySlug(string $slug): ?UsedLocale;
}
