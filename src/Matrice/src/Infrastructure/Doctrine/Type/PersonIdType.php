<?php
declare(strict_types=1);

namespace Matrice\Infrastructure\Doctrine\Type;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Matrice\Domain\Model\Skillmatrix\PersonId;
use Ramsey\Uuid\Doctrine\UuidType;

final class PersonIdType extends UuidType
{
    public const NAME = 'person_id';

    public function getName(): string
    {
        return self::NAME;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): ?PersonId
    {
        $uuid = parent::convertToPHPValue($value, $platform);

        return $uuid ? PersonId::fromUuid($uuid) : null;
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        if ($value instanceof PersonId) {
            $value = $value->toUuid();
        }

        return parent::convertToDatabaseValue($value, $platform);
    }
}
