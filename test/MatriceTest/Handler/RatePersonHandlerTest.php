<?php
declare(strict_types=1);

namespace MatriceTest\Handler;

use Matrice\Application\Command\RatePerson;
use Matrice\Application\Handler\RatePersonHandler;
use Matrice\Domain\Model\Skillmatrix\Skillmatrix;
use Matrice\Domain\Model\Skillmatrix\SkillmatrixId;
use Matrice\Domain\Model\Skillmatrix\SkillmatrixRepository;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;

class RatePersonHandlerTest extends TestCase
{
    use ProphecyTrait;

    public function testCreate(): void
    {
        $skillmatrix = $this->prophesize(Skillmatrix::class);
        $skillmatrixRepository = $this->prophesize(SkillmatrixRepository::class);
        $skillmatrixRepository->get(SkillmatrixId::fromString('a74ef908-dee6-407f-bcd9-692f9fd4804c'))
            ->shouldBeCalledOnce()
            ->willReturn($skillmatrix->reveal());

        $handler = new RatePersonHandler($skillmatrixRepository->reveal());

        $data = [
            'personId' => 'a863bba4-2f50-4664-a381-79e0bf54e81d',
            'skillId' => '24d32e24-c2f4-4d55-8ffa-6f22ef82e201',
            'reviewer' => [
                'id' => '93faef13-f879-4ff1-94f8-8396b780b31a',
                'name' => 'Reviewer 1',
            ],
            'score' => 3,
            'note' => 'Test rating 1',
        ];

        $command = RatePerson::fromArray(
            SkillmatrixId::fromString('a74ef908-dee6-407f-bcd9-692f9fd4804c'),
            $data,
        );

        $handler->handle($command);
    }
}
