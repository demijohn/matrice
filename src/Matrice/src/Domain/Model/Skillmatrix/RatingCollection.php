<?php
declare(strict_types=1);

namespace Matrice\Domain\Model\Skillmatrix;

use ArrayIterator;
use Countable;
use IteratorAggregate;
use JsonSerializable;

final class RatingCollection implements IteratorAggregate, Countable, JsonSerializable
{
    /**
     * @var Rating[]
     */
    private array $ratings;

    public static function fromArray(array $data): self
    {
        $ratings = array_map([Rating::class, 'fromArray'], $data);

        return new self(...$ratings);
    }

    public function __construct(Rating ...$ratings)
    {
        $this->ratings = $ratings;
    }

    /**
     * @return ArrayIterator|Rating[]
     */
    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->ratings);
    }

    public function count(): int
    {
        return \count($this->ratings);
    }

    public function jsonSerialize(): array
    {
        return $this->ratings;
    }
}
