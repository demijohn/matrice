<?php
declare(strict_types=1);

namespace Matrice\Infrastructure\Doctrine\Type;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Matrice\Domain\Model\Skillmatrix\SkillId;
use Ramsey\Uuid\Doctrine\UuidType;

final class SkillIdType extends UuidType
{
    public const NAME = 'skill_id';

    public function getName(): string
    {
        return self::NAME;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): ?SkillId
    {
        $uuid = parent::convertToPHPValue($value, $platform);

        return $uuid ? SkillId::fromUuid($uuid) : null;
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        if ($value instanceof SkillId) {
            $value = $value->toUuid();
        }

        return parent::convertToDatabaseValue($value, $platform);
    }
}
