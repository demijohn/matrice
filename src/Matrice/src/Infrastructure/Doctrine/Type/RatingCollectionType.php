<?php
declare(strict_types=1);

namespace Matrice\Infrastructure\Doctrine\Type;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\JsonType;
use Matrice\Domain\Model\Skillmatrix\RatingCollection;

final class RatingCollectionType extends JsonType
{
    public const NAME = 'rating_collection';

    public function getName(): string
    {
        return self::NAME;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): ?RatingCollection
    {
        $value = parent::convertToPHPValue($value, $platform);

        if ($value === null) {
            return null;
        }

        return RatingCollection::fromArray($value);
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform): bool
    {
        return true;
    }
}
