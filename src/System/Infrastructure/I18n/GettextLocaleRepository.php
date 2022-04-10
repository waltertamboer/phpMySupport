<?php

declare(strict_types=1);

namespace Support\System\Infrastructure\I18n;

use Gettext\Languages\Language;
use Support\System\Domain\I18n\Locale;
use Support\System\Domain\I18n\LocaleList;
use Support\System\Domain\I18n\LocaleRepository;

final class GettextLocaleRepository implements LocaleRepository
{
    public function lookup(string $id): ?Locale
    {
        $language = Language::getById($id);

        if ($language === null) {
            return null;
        }

        return new Locale($language->id, $language->name);
    }

    public function query(string $query): LocaleList
    {
        $languages = Language::getAll();
        $languages = array_filter($languages, static function (Language $item) use ($query) {
            return strpos(strtolower($item->name), strtolower($query)) !== false;
        });
        $languages = array_values($languages);
        $languages = array_map(static function (Language $language): Locale {
            return new Locale($language->id, $language->name);
        }, $languages);

        return new LocaleList($languages);
    }
}
