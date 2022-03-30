<?php

declare(strict_types=1);

namespace Support\System\Domain\Criteria;

enum OrderType
{
    case Ascending;
    case Descending;
    case None;
}
