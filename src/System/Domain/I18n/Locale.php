<?php

declare(strict_types=1);

namespace Support\System\Domain\I18n;

final class Locale
{
    public function __construct(
        private readonly string $id,
        private readonly string $name,
        private readonly string $country,
    ) {
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getCountry(): string
    {
        return $this->country;
    }
}
