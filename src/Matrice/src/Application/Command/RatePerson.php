<?php
declare(strict_types=1);

namespace Matrice\Application\Command;

use Matrice\Domain\Model\Skillmatrix\PersonId;
use Matrice\Domain\Model\Skillmatrix\Reviewer;
use Matrice\Domain\Model\Skillmatrix\SkillId;
use Matrice\Domain\Model\Skillmatrix\SkillmatrixId;

final class RatePerson
{
    private SkillmatrixId $skillmatrixId;

    private PersonId $personId;

    private SkillId $skillId;

    private Reviewer $reviewer;

    private int $score;

    private string $note;

    public static function fromArray(SkillmatrixId $skillmatrixId, array $data): self
    {
        return new self(
            $skillmatrixId,
            PersonId::fromString($data['personId']),
            SkillId::fromString($data['skillId']),
            Reviewer::fromArray($data['reviewer']),
            $data['score'],
            $data['note'],
        );
    }

    private function __construct(
        SkillmatrixId $id,
        PersonId $personId,
        SkillId $skillId,
        Reviewer $reviewer,
        int $score,
        string $note
    ) {
        $this->skillmatrixId = $id;
        $this->personId = $personId;
        $this->skillId = $skillId;
        $this->reviewer = $reviewer;
        $this->score = $score;
        $this->note = $note;
    }

    public function getSkillmatrixId(): SkillmatrixId
    {
        return $this->skillmatrixId;
    }

    public function getPersonId(): PersonId
    {
        return $this->personId;
    }

    public function getSkillId(): SkillId
    {
        return $this->skillId;
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
}
