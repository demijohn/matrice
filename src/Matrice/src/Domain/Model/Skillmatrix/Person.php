<?php
declare(strict_types=1);

namespace Matrice\Domain\Model\Skillmatrix;

use Assert\Assertion;
use JsonSerializable;

final class Person implements JsonSerializable
{
    private PersonId $id;

    private string $name;

    public static function fromArray(array $data): self
    {
        return new self(
            PersonId::fromString($data['id']),
            $data['name'],
        );
    }

    private function __construct(PersonId $id, string $name)
    {
        Assertion::betweenLength($name, 3, 50);

        $this->id = $id;
        $this->name = $name;
    }

    public function getId(): PersonId
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function equals(self $person): bool
    {
        return $this->id->equals($person->getId());
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
        ];
    }
}
