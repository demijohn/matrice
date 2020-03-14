<?php
declare(strict_types=1);

namespace Matrice\Domain\Model\Skillmatrix;

use ArrayIterator;
use Countable;
use IteratorAggregate;

final class ReviewerCollection implements IteratorAggregate, Countable
{
    /**
     * @var Reviewer[]
     */
    private array $reviewers;

    public function __construct(Reviewer ...$reviewers)
    {
        $this->reviewers = $reviewers;
    }

    /**
     * @return ArrayIterator|Reviewer[]
     */
    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->reviewers);
    }

    public function count(): int
    {
        return \count($this->reviewers);
    }
}
