<?php

declare(strict_types=1);

namespace Support\System\Infrastructure\Doctrine\ORM\Type;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use Support\KnowledgeBase\Domain\Category\CategoryName;
use Support\System\Domain\Value\IPAddress;

final class InetType extends Type
{
    public const NAME = 'inet';

    /**
     * {@inheritdoc}
     */
    public function getSQLDeclaration(array $column, AbstractPlatform $platform)
    {
        return 'INET';
    }

    /**
     * @return mixed|CategoryName
     */
    public function convertToPHPValue($value, AbstractPlatform $platform) // phpcs:ignore
    {
        if (is_string($value)) {
            return new IPAddress($value);
        }

        return $value;
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if ($value instanceof IPAddress) {
            return $value->address;
        }

        return $value;
    }

    /**
     * @return string
     */
    public function getName() // phpcs:ignore
    {
        return self::NAME;
    }
}
