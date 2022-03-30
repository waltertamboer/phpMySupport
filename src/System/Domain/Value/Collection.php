<?php

declare(strict_types=1);

namespace Support\System\Domain\Value;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * @template TValue
 * @extends ArrayCollection<int, TValue>
 */
class Collection extends ArrayCollection
{
}
