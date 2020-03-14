<?php
declare(strict_types=1);

namespace Matrice\Domain\Model\Skillmatrix;

use ArrayIterator;
use Countable;
use IteratorAggregate;

final class PersonCollection implements IteratorAggregate, Countable
{
    /**
     * @var Person[]
     */
    private array $persons;

    public static function jsonDeserialize(array $data): self
    {
        $persons = array_map([Person::class, 'fromArray'], $data);

        return new self(...$persons);
    }

    public function __construct(Person ...$persons)
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
}
