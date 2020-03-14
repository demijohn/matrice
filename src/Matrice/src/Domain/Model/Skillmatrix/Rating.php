<?php
declare(strict_types=1);

namespace Matrice\Domain\Model\Skillmatrix;

use Assert\Assertion;
use DateTime;
use JsonSerializable;

final class Rating implements JsonSerializable
{
    private PersonId $personId;

    private SkillId $skillId;

    private Reviewer $reviewer;

    private int $score;

    private string $note;

    private DateTime $date;

    public static function create(
        PersonId $personId,
        SkillId $skillId,
        Reviewer $reviewer,
        int $score,
        string $note
    ): self {
        Assertion::between($score, 0, 5);

        return new self(
            $personId,
            $skillId,
            $reviewer,
            $score,
            $note,
            new DateTime('now'),
        );
    }

    private function __construct(
        PersonId $personId,
        SkillId $skillId,
        Reviewer $reviewer,
        int $score,
        string $note,
        DateTime $date
    ) {
        $this->personId = $personId;
        $this->skillId = $skillId;
        $this->reviewer = $reviewer;
        $this->score = $score;
        $this->note = $note;
        $this->date = $date;
    }

    public function getSkillId(): SkillId
    {
        return $this->skillId;
    }

    public function getPersonId(): PersonId
    {
        return $this->personId;
    }

    public function getReviewer(): Reviewer
    {
        return $this->reviewer;
    }

    public function getScore(): int
    {
        return $this->score;
    }

    public function getNote(): string
    {
        return $this->note;
    }

    public function jsonSerialize(): array
    {
        return [
            'personId' => $this->personId,
            'skillId' => $this->skillId,
            'reviewer' => $this->reviewer,
            'score' => $this->score,
            'note' => $this->note,
            'date' => $this->date,
        ];
    }

    public function equals(Rating $rating): bool
    {
        // Two ratings are considered equal if they are for the same Person and Skill
        return $this->personId->equals($rating->getPersonId()) && $this->skillId->equals($rating->getSkillId());
    }
}
