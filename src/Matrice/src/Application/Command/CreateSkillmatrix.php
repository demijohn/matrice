<?php
declare(strict_types=1);

namespace Matrice\Application\Command;

use Matrice\Domain\Model\Skillmatrix\PersonCollection;
use Matrice\Domain\Model\Skillmatrix\SkillCollection;
use Matrice\Domain\Model\Skillmatrix\SkillmatrixId;

final class CreateSkillmatrix
{
    private SkillmatrixId $id;

    private PersonCollection $persons;

    private SkillCollection $skills;

    // TODO: zvazit ci potrebujem factory metodu ked je rovnaka ako konstruktor
    public static function create(SkillmatrixId $id, PersonCollection $persons, SkillCollection $skills): self
    {
        return new self($id, $persons, $skills);
    }

    private function __construct(SkillmatrixId $id, PersonCollection $persons, SkillCollection $skills)
    {
        $this->id = $id;
        $this->persons = $persons;
        $this->skills = $skills;
    }

    public function getId(): SkillmatrixId
    {
        return $this->id;
    }

    public function getPersons(): PersonCollection
    {
        return $this->persons;
    }

    public function getSkills(): SkillCollection
    {
        return $this->skills;
    }
}
