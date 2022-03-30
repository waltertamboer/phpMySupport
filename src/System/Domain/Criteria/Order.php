<?php

declare(strict_types=1);

namespace Support\System\Domain\Criteria;

final class Order
{
    public function __construct(
        public readonly OrderBy $orderBy,
        public readonly OrderType $orderType
    ) {
    }
}
