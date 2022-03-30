<?php

declare(strict_types=1);

namespace Support\KnowledgeBase\Infrastructure\Doctrine\ORM\Type;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;
use Support\KnowledgeBase\Domain\Category\CategoryName;
use Support\KnowledgeBase\Domain\Category\CategorySlug;

final class CategorySlugType extends StringType
{
    public const NAME = 'category_slug';

    /**
     * @return mixed|CategoryName
     */
    public function convertToPHPValue($value, AbstractPlatform $platform) // phpcs:ignore
    {
        if (is_string($value)) {
            return new CategorySlug($value);
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
