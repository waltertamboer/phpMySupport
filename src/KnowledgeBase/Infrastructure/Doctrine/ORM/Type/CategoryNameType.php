<?php

declare(strict_types=1);

namespace Support\KnowledgeBase\Infrastructure\Doctrine\ORM\Type;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;
use Support\KnowledgeBase\Domain\Category\CategoryName;

final class CategoryNameType extends StringType
{
    public const NAME = 'category_name';

    /**
     * @return mixed|CategoryName
     */
    public function convertToPHPValue($value, AbstractPlatform $platform) // phpcs:ignore
    {
        if (is_string($value)) {
            return new CategoryName($value);
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
