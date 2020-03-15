<?php
declare(strict_types=1);

namespace Matrice\Infrastructure\Doctrine\Type;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Matrice\Domain\Model\Skillmatrix\ReviewerId;
use Ramsey\Uuid\Doctrine\UuidType;

final class ReviewerIdType extends UuidType
{
    public const NAME = 'reviewer_id';

    public function getName(): string
    {
        return self::NAME;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): ?ReviewerId
    {
        $uuid = parent::convertToPHPValue($value, $platform);

        return $uuid ? ReviewerId::fromUuid($uuid) : null;
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        if ($value instanceof ReviewerId) {
            $value = $value->toUuid();
        }

        return parent::convertToDatabaseValue($value, $platform);
    }
}
