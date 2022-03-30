<?php

declare(strict_types=1);

namespace Support\System\Domain\Value;

class UnicodeString extends StringValue
{
    public function length(): int
    {
        return mb_strlen($this->value);
    }

    public function substring(int $position, ?int $length = null): StringValue
    {
        return new UnicodeString(mb_substr($this->value, $position, $length));
    }

    public function toLowercase(): StringValue
    {
        return new UnicodeString(mb_strtolower($this->value));
    }

    public function toUppercase(): StringValue
    {
        return new UnicodeString(mb_strtoupper($this->value));
    }

    public function equals(StringValue $other): bool
    {
        $encoding = mb_internal_encoding();

        return strcmp(
            mb_strtoupper($this->value, $encoding),
            mb_strtoupper($other->value, $encoding)
        ) === 0;
    }
}
