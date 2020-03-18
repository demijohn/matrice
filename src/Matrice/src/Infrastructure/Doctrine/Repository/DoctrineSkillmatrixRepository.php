<?php
declare(strict_types=1);

namespace Matrice\Infrastructure\Doctrine\Repository;

use Doctrine\ORM\EntityManagerInterface;
use Matrice\Domain\Model\Skillmatrix\Exception\SkillmatrixNotFound;
use Matrice\Domain\Model\Skillmatrix\Skillmatrix;
use Matrice\Domain\Model\Skillmatrix\SkillmatrixId;
use Matrice\Domain\Model\Skillmatrix\SkillmatrixRepository;

final class DoctrineSkillmatrixRepository implements SkillmatrixRepository
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function get(SkillmatrixId $skillmatrixId): Skillmatrix
    {
        /** @var Skillmatrix|null $skillmatrix */
        $skillmatrix = $this->em->find(Skillmatrix::class, $skillmatrixId);

        if ($skillmatrix === null) {
            throw new SkillmatrixNotFound(sprintf('Skillmatrix "%s" not found', $skillmatrixId));
        }

        return $skillmatrix;
    }

    public function add(Skillmatrix $skillmatrix): void
    {
        $this->em->persist($skillmatrix);
    }

    public function nextIdentity(): SkillmatrixId
    {
        return SkillmatrixId::generate();
    }
}
