<?php

declare(strict_types=1);

namespace Support\KnowledgeBase\Infrastructure\Doctrine\ORM\Type;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Ramsey\Uuid\Doctrine\UuidType;
use Support\KnowledgeBase\Domain\Category\CategoryId;

final class CategoryIdType extends UuidType
{
    public const NAME = 'category_id';

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        if (is_string($value)) {
            return new CategoryId($value);
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
