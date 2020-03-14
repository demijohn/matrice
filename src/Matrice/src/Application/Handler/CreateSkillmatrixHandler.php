<?php
declare(strict_types=1);

namespace Matrice\Application\Handler;

use Matrice\Application\Command\CreateSkillmatrix;
use Matrice\Domain\Model\Skillmatrix\Skillmatrix;
use Matrice\Domain\Model\Skillmatrix\SkillmatrixRepository;

final class CreateSkillmatrixHandler
{
    private SkillmatrixRepository $skillmatrixRepository;

    public function __construct(SkillmatrixRepository $skillmatrixRepository)
    {
        $this->skillmatrixRepository = $skillmatrixRepository;
    }

    public function handle(CreateSkillmatrix $command): void
    {
        $skillmatrix = Skillmatrix::create(
            $command->getId(),
            $command->getPersons(),
            $command->getSkills(),
        );

        $this->skillmatrixRepository->add($skillmatrix);
    }
}
