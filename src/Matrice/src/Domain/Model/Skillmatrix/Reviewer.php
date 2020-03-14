<?php
declare(strict_types=1);

namespace Matrice\Domain\Model\Skillmatrix;

use Assert\Assertion;
use JsonSerializable;

final class Reviewer implements JsonSerializable
{

    private ReviewerId $id;

    private string $name;

    public static function create(ReviewerId $id, string $name): self
    {
        Assertion::betweenLength($name, 3, 50);

        return new self($id, $name);
    }

    private function __construct(ReviewerId $id, string $name)
    {
        $this->id = $id;
        $this->name = $name;
    }

    public function getId(): ReviewerId
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
        ];
    }
}
