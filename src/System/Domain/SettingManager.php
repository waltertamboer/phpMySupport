<?php

declare(strict_types=1);

namespace Support\System\Domain;

interface SettingManager
{
    public function get(string $name, string $defaultValue = ''): string;

    public function set(string $name, string $value): void;
}
