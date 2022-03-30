<?php

declare(strict_types=1);

namespace Support\System\Infrastructure\Doctrine\ORM\Type;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use Support\KnowledgeBase\Domain\Category\CategoryName;
use Support\System\Domain\Value\IPAddress;
use Support\System\Domain\Value\UserAgent;

final class UserAgentType extends Type
{
    public const NAME = 'user_agent';

    /**
     * {@inheritdoc}
     */
    public function getSQLDeclaration(array $column, AbstractPlatform $platform)
    {
        return $platform->getVarcharTypeDeclarationSQL($column);
    }

    /**
     * @return mixed|CategoryName
     */
    public function convertToPHPValue($value, AbstractPlatform $platform) // phpcs:ignore
    {
        if (is_string($value)) {
            return new UserAgent($value);
        }

        return $value;
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if ($value instanceof UserAgent) {
            return $value->userAgent;
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
