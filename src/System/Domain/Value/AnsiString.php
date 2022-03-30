<?php

declare(strict_types=1);

namespace Support\System\Domain\Value;

class AnsiString extends StringValue
{
    public function length(): int
    {
        return strlen($this->value);
    }

    public function substring(int $position, ?int $length = null): StringValue
    {
        return new AnsiString(substr($this->value, $position, $length));
    }

    public function toLowercase(): StringValue
    {
        return new AnsiString(strtolower($this->value));
    }

    public function toUppercase(): StringValue
    {
        return new AnsiString(strtoupper($this->value));
    }

    public function equals(StringValue $other): bool
    {
        return strcmp($this->value, $other->value) === 0;
    }
}
