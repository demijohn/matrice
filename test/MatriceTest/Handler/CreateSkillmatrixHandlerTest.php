<?php
declare(strict_types=1);

namespace MatriceTest\Handler;

use Matrice\Application\Command\CreateSkillmatrix;
use Matrice\Application\Handler\CreateSkillmatrixHandler;
use Matrice\Domain\Model\Skillmatrix\PersonCollection;
use Matrice\Domain\Model\Skillmatrix\SkillCollection;
use Matrice\Domain\Model\Skillmatrix\Skillmatrix;
use Matrice\Domain\Model\Skillmatrix\SkillmatrixId;
use Matrice\Domain\Model\Skillmatrix\SkillmatrixRepository;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;

class CreateSkillmatrixHandlerTest extends TestCase
{
    use ProphecyTrait;

    public function testCreate(): void
    {
        $skillmatrixRepository = $this->prophesize(SkillmatrixRepository::class);
        $skillmatrixRepository->add(Argument::type(Skillmatrix::class))
            ->shouldBeCalledOnce();
        $handler = new CreateSkillmatrixHandler($skillmatrixRepository->reveal());

        $persons = PersonCollection::fromArray([
            [
                'id' => 'a863bba4-2f50-4664-a381-79e0bf54e81d',
                'name' => 'Person 1',
            ],
        ]);

        $skills = SkillCollection::fromArray([
            [
                'id' => '24d32e24-c2f4-4d55-8ffa-6f22ef82e201',
                'name' => 'Skill 1',
            ],
        ]);

        $command = new CreateSkillmatrix(
            SkillmatrixId::fromString('a74ef908-dee6-407f-bcd9-692f9fd4804c'),
            $persons,
            $skills,
        );

        $handler->handle($command);
    }
}
