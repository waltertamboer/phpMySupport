<?php

declare(strict_types=1);

namespace Support\System\Domain\I18n\Bus\Query;

final class GetUsedLocaleBySlug
{
    public function __construct(
        public readonly string $slug,
    ) {
    }
}
