<?php

declare(strict_types=1);

namespace Support\System\Domain\Value;

use Stringable;

abstract class StringValue implements Stringable
{
    public function __construct(
        protected string $value
    ) {
    }

    /**
     * Calculates the length of the string in characters.
     *
     * @return int Returns the amount of characters this string value has.
     */
    abstract public function length(): int;

    /**
     * Retrieves a portion of this string.
     *
     * @param int $position The position to
     * @param int|null $length The amount of characters to extract; null to get everything.
     * @return StringValue Returns a new instance containing the result.
     */
    abstract public function substring(int $position, ?int $length = null): StringValue;

    /**
     * Converts the string to all lowercase characters.
     *
     * @return StringValue Returns a new instance containing the result.
     */
    abstract public function toLowercase(): StringValue;

    /**
     * Converts the string to all uppercase characters.
     *
     * @return StringValue Returns a new instance containing the result.
     */
    abstract public function toUppercase(): StringValue;

    /**
     * Compares the given instance with this string.
     *
     * @param StringValue $other The other instance to compare with.
     * @return StringValue Returns a new instance of a StringValue containing the result.
     */
    abstract public function equals(StringValue $other): bool;

    /**
     * Gets the raw string data.
     *
     * @return string Returns the raw string data.
     */
    public function value(): string
    {
        return $this->value;
    }

    /**
     * Gets the raw string data.
     *
     * @return string Returns the raw string data.
     */
    public function __toString(): string
    {
        return $this->value();
    }
}
