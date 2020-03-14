<?php
declare(strict_types=1);

namespace Matrice\Domain\Model\Skillmatrix;

use ArrayIterator;
use Countable;
use IteratorAggregate;

final class RatingCollection implements IteratorAggregate, Countable
{
    /**
     * @var Rating[]
     */
    private array $ratings;

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
}
