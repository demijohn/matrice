<?php
declare(strict_types=1);

namespace Matrice\Domain\Model\Skillmatrix;

use ArrayIterator;
use Countable;
use IteratorAggregate;
use JsonSerializable;

final class PersonCollection implements IteratorAggregate, Countable, JsonSerializable
{
    /**
     * @var Person[]
     */
    private array $persons;

    public static function fromArray(array $data): self
    {
        $persons = array_map([Person::class, 'fromArray'], $data);

        return new self(...$persons);
    }

    private function __construct(Person ...$persons)
    {
        $this->persons = $persons;
    }

    /**
     * @return ArrayIterator|Person[]
     */
    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->persons);
    }

    public function count(): int
    {
        return \count($this->persons);
    }

    public function jsonSerialize(): array
    {
        return $this->persons;
    }
}
