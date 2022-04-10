<?php

declare(strict_types=1);

namespace Support\System\Domain;

class Setting
{
    public function __construct(
        private readonly string $name,
        private string $value,
    ) {
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function setValue(string $value): void
    {
        $this->value = $value;
    }
}
