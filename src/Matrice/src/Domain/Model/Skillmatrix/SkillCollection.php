<?php
declare(strict_types=1);

namespace Matrice\Domain\Model\Skillmatrix;

use ArrayIterator;
use Countable;
use IteratorAggregate;
use JsonSerializable;

final class SkillCollection implements IteratorAggregate, Countable, JsonSerializable
{
    /**
     * @var Skill[]
     */
    private array $skills;

    public static function fromArray(array $data): self
    {
        $skills = array_map([Skill::class, 'fromArray'], $data);

        return new self(...$skills);
    }

    public function __construct(Skill ...$skills)
    {
        $this->skills = $skills;
    }

    /**
     * @return ArrayIterator|Skill[]
     */
    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->skills);
    }

    public function count(): int
    {
        return \count($this->skills);
    }

    public function jsonSerialize(): array
    {
        return $this->skills;
    }
}
