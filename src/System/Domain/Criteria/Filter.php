<?php

declare(strict_types=1);

namespace Support\System\Domain\Criteria;

final class Filter
{
    public function __construct(
        public readonly FilterField $field,
        public readonly FilterOperator $operator,
        public readonly FilterValue $value
    ) {
    }
}
