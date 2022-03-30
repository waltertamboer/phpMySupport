<?php

declare(strict_types=1);

namespace Support\System\Domain\Bus\Query;

use Support\System\Domain\Bus\Query\Exception\InvalidQuery;

interface QueryBus
{
    /**
     * @template TQuery
     * @template TQueryResponse
     *
     * @param TQuery $query
     * @return TQueryResponse
     * @throws InvalidQuery
     */
    public function query($query);
}
