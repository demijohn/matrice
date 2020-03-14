<?php
declare(strict_types=1);

namespace Matrice\Domain\Model\Skillmatrix;

use Assert\Assertion;
use JsonSerializable;

final class Skill implements JsonSerializable
{
    private SkillId $id;

    private string $name;

    public static function create(SkillId $id, string $name): self
    {
        Assertion::betweenLength($name, 3, 255);

        return new self($id, $name);
    }

    public static function fromArray(array $data): self
    {
        return new self(
            SkillId::fromString($data['id']),
            $data['name'],
        );
    }

    private function __construct(SkillId $id, string $name)
    {
        $this->id = $id;
        $this->name = $name;
    }

    public function getId(): SkillId
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function equals(self $skill): bool
    {
        return $this->id->equals($skill->getId());
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
        ];
    }
}
