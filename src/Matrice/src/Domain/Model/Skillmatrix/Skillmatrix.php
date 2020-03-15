<?php
declare(strict_types=1);

namespace Matrice\Domain\Model\Skillmatrix;

use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;
use Matrice\Domain\Model\Skillmatrix\Exceptions\RatingAlreadyExists;

/**
 * @ORM\Entity
 */
class Skillmatrix implements JsonSerializable
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="skillmatrix_id")
     */
    private SkillmatrixId $id;

    /**
     * @ORM\Column(type="person_collection")
     */
    private PersonCollection $persons;

    /**
     * @ORM\Column(type="skill_collection")
     */
    private SkillCollection $skills;

    /**
     * @ORM\Column(type="rating_collection", nullable=true)
     */
    private ?RatingCollection $ratings;

    public static function create(SkillmatrixId $id, PersonCollection $persons, SkillCollection $skills): self
    {
        return new self($id, $persons, $skills);
    }

    private function __construct(SkillmatrixId $id, PersonCollection $persons, SkillCollection $skills)
    {
        $this->id = $id;
        $this->persons = $persons;
        $this->skills = $skills;
        $this->ratings = null;
    }

    public function addRating(Rating $rating): void
    {
        if ($this->ratingExists($rating)) {
            throw new RatingAlreadyExists('Rating already exists.');
        }

        if ($this->ratings === null) {
            $this->ratings = new RatingCollection($rating);
        }

        $this->ratings = new RatingCollection($this->ratings->getIterator(), $rating);
    }

    public function getRatings(): RatingCollection
    {
        return $this->ratings;
    }

    private function ratingExists(Rating $rating): bool
    {
        /** @var Rating $existingRating */
        foreach ($this->ratings as $existingRating) {
            if ($rating->equals($existingRating)) {
                return true;
            }
        }

        return false;
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'persons' => $this->persons,
            'skills' => $this->skills,
            'ratings' => $this->ratings,
        ];
    }
}
