<?php
declare(strict_types=1);

namespace MatriceTest\Repository;

use Doctrine\ORM\EntityManagerInterface;
use Matrice\Domain\Model\Skillmatrix\Exception\SkillmatrixNotFound;
use Matrice\Domain\Model\Skillmatrix\Skillmatrix;
use Matrice\Domain\Model\Skillmatrix\SkillmatrixId;
use Matrice\Infrastructure\Doctrine\Repository\DoctrineSkillmatrixRepository;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;

class DoctrineSkillmatrixRepositoryTest extends TestCase
{
    use ProphecyTrait;

    public function testSkillmatrixNotFoundException(): void
    {
        $skillmatrixId = SkillmatrixId::generate();
        $em = $this->prophesize(EntityManagerInterface::class);
        $em->find(Skillmatrix::class, $skillmatrixId)
            ->shouldBeCalledOnce()
            ->willReturn(null);
        $repository = new DoctrineSkillmatrixRepository($em->reveal());

        $this->expectExceptionObject(SkillmatrixNotFound::create($skillmatrixId));

        $repository->get($skillmatrixId);
    }
}
