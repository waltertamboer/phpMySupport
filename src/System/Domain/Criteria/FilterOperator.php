<?php

declare(strict_types=1);

namespace Support\System\Domain\Criteria;

enum FilterOperator
{
    case Equal;
    case NotEqual;
    case GreaterThan;
    case GreaterThanOrEqual;
    case LessThan;
    case LessThanOrEqual;
    case Contains;
    case NotContains;
}
