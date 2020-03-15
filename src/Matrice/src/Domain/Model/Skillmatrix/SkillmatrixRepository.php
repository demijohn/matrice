<?php
declare(strict_types=1);

namespace Matrice\Domain\Model\Skillmatrix;

interface SkillmatrixRepository
{
    public function get(SkillmatrixId $skillmatrixId): Skillmatrix;

    public function add(Skillmatrix $skillmatrix): void;

    public function nextIdentity(): SkillmatrixId;
}
