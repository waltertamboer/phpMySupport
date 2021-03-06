<?php

declare(strict_types=1);

namespace Support\System\Infrastructure\I18n;

use Gettext\Languages\Language;
use Support\System\Domain\I18n\Locale;
use Support\System\Domain\I18n\LocaleList;
use Support\System\Domain\I18n\LocaleQueryRepository;

final class GettextLocaleQueryRepository implements LocaleQueryRepository
{
    public function lookup(string $id): ?Locale
    {
        $language = Language::getById($id);

        if ($language === null) {
            return null;
        }

        return $this->convertLanguageToLocale($language);
    }

    public function query(string $query): LocaleList
    {
        $languages = Language::getAll();
        $languages = array_filter($languages, static function (Language $item) use ($query) {
            return strpos(strtolower($item->name), strtolower($query)) !== false;
        });
        $languages = array_values($languages);
        $languages = array_map(function (Language $language): Locale {
            return $this->convertLanguageToLocale($language);
        }, $languages);

        return new LocaleList($languages);
    }

    private function convertLanguageToLocale(Language $language): Locale
    {
        $splitted = explode('_', $language->id);

        if (!isset($splitted[1])) {
            $country = $splitted[0];
        } else {
            $country = strtolower($splitted[1]);
        }

        if ($country === 'zh' || $country === 'hans') {
            $country = 'cn';
        }

        return new Locale($language->id, $language->name, $country);
    }
}
