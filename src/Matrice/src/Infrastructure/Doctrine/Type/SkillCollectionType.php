<?php
declare(strict_types=1);

namespace Matrice\Infrastructure\Doctrine\Type;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\JsonType;
use Matrice\Domain\Model\Skillmatrix\SkillCollection;

final class SkillCollectionType extends JsonType
{
    public const NAME = 'skill_collection';

    public function getName(): string
    {
        return self::NAME;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): ?SkillCollection
    {
        $value = parent::convertToPHPValue($value, $platform);

        if ($value === null) {
            return null;
        }

        return SkillCollection::fromArray($value);
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform): bool
    {
        return true;
    }
}
