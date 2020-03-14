<?php
declare(strict_types=1);

namespace Matrice\Domain\Model\Skillmatrix;

use JsonSerializable;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

final class ReviewerId implements JsonSerializable
{
    private UuidInterface $value;

    public static function generate(): self
    {
        return new self(Uuid::uuid4());
    }

    public static function fromString(string $value): self
    {
        return new self(Uuid::fromString($value));
    }

    public static function fromUuid(UuidInterface $uuid): self
    {
        return new self($uuid);
    }

    private function __construct(UuidInterface $uuid)
    {
        $this->value = $uuid;
    }

    public function __toString()
    {
        return $this->value->toString();
    }

    public function toUuid(): UuidInterface
    {
        return $this->value;
    }

    public function jsonSerialize()
    {
        return $this->value->toString();
    }
}
