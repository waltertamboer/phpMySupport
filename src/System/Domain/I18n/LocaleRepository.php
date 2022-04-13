<?php

declare(strict_types=1);

namespace Support\System\Domain\I18n;

interface LocaleRepository
{
    public function getUsedLocales(): LocaleList;
    public function lookup(string $id): ?Locale;
    public function query(string $query): LocaleList;
}
