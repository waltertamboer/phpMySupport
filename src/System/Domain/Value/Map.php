<?php

declare(strict_types=1);

namespace Support\System\Domain\Value;

use ArrayIterator;
use Countable;
use IteratorAggregate;
use Support\System\Domain\Value\Exception\KeyNotFound;
use Traversable;

use function array_key_exists;
use function array_keys;
use function array_values;
use function count;

/**
 * @template TKey
 * @template TValue
 */
abstract class Map implements Countable, IteratorAggregate
{
    /**
     * array<TKey, TValue> $items
     */
    public function __construct(
        protected array $items = [],
    ) {
    }

    /**
     * @param TKey $key
     *
     * @return TValue
     *
     * @throws KeyNotFound
     */
    public function get($key)
    {
        if (!$this->has($key)) {
            throw KeyNotFound::fromKey($key);
        }

        return $this->items[$key];
    }

    /**
     * @param TKey $key
     *
     * @return TValue|null
     */
    public function find($key)
    {
        if (!$this->has($key)) {
            return null;
        }

        return $this->items[$key];
    }

    /**
     * @param TKey $key
     */
    public function has($key): bool
    {
        return array_key_exists($key, $this->items);
    }

    /**
     * @param TKey $key
     * @param TValue $value
     */
    public function set($key, $value): void
    {
        $this->items[$key] = $value;
    }

    /**
     * @return TKey[]
     */
    public function keys(): array
    {
        return array_keys($this->items);
    }

    /**
     * @return TValue[]
     */
    public function values(): array
    {
        return array_values($this->items);
    }

    public function count(): int
    {
        return count($this->items);
    }

    /**
     * @return Traversable<TKey, TValue>
     */
    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->items);
    }
}
