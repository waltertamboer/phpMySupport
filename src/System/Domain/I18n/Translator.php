<?php

declare(strict_types=1);

namespace Support\System\Domain\I18n;

interface Translator
{
    public function translate(?string $id, array $parameters = [], string $domain = null, string $locale = null);

    public function setLocale(string $locale): void;
}
