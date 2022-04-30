<?php

declare(strict_types=1);

namespace Support\KnowledgeBase\Domain\Category\Bus\Query;

use Support\System\Domain\I18n\UsedLocale;

final class GetCategoryOverview
{
    public function __construct(
        private readonly UsedLocale $locale,
    ) {
    }

    public function getLocale(): UsedLocale
    {
        return $this->locale;
    }
}
