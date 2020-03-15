<?php
declare(strict_types=1);

namespace Matrice\Infrastructure\Doctrine\Type;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Matrice\Domain\Model\Skillmatrix\SkillmatrixId;
use Ramsey\Uuid\Doctrine\UuidType;

final class SkillmatrixIdType extends UuidType
{
    public const NAME = 'skillmatrix_id';

    public function getName(): string
    {
        return self::NAME;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): ?SkillmatrixId
    {
        $uuid = parent::convertToPHPValue($value, $platform);

        return $uuid ? SkillmatrixId::fromUuid($uuid) : null;
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        if ($value instanceof SkillmatrixId) {
            $value = $value->toUuid();
        }

        return parent::convertToDatabaseValue($value, $platform);
    }
}
