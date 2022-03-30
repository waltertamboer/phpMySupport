<?php

declare(strict_types=1);

namespace Support\System\Domain\Criteria;

final class Criteria
{
    public function __construct(
        public readonly Filters $filters,
        public readonly ?Orders $orders = null,
        public readonly ?int $offset = null,
        public readonly ?int $limit = null
    ) {
    }
}
