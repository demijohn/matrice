<?php
declare(strict_types=1);

namespace Matrice\Application\Handler;

use Matrice\Application\Command\RatePerson;
use Matrice\Domain\Model\Skillmatrix\Rating;
use Matrice\Domain\Model\Skillmatrix\SkillmatrixRepository;

final class RatePersonHandler
{
    private SkillmatrixRepository $skillmatrixRepository;

    public function __construct(SkillmatrixRepository $skillmatrixRepository)
    {
        $this->skillmatrixRepository = $skillmatrixRepository;
    }

    public function handle(RatePerson $command): void
    {
        $rating = Rating::create(
            $command->getPersonId(),
            $command->getSkillId(),
            $command->getReviewer(),
            $command->getScore(),
            $command->getNote(),
        );

        $skillmatrix = $this->skillmatrixRepository->get($command->getSkillmatrixId());
        $skillmatrix->addRating($rating);
    }
}
