<?php
declare(strict_types=1);

namespace MatriceTest\Integration\Action;

use Assert\Assertion;
use Matrice\Domain\Model\Skillmatrix\PersonId;
use Matrice\Domain\Model\Skillmatrix\SkillId;
use Matrice\Domain\Model\Skillmatrix\SkillmatrixId;
use Matrice\Library\Test\TestCase;
use Mezzio\Application;

class RatePersonActionTest extends TestCase
{
    public function setUp(): void
    {
        $container = require __DIR__ . '/../../container.php';
        $this->app = $container->get(Application::class);
    }

    public function testRatePerson(): void
    {
        $person1Id = PersonId::generate();
        $person2Id = PersonId::generate();
        $person3Id = PersonId::generate();
        $skill1Id = SkillId::generate();
        $skill2Id = SkillId::generate();
        $skill3Id = SkillId::generate();
        $skill4Id = SkillId::generate();

        // 1. Create Skillmatrix
        $skillmatrix = [
            'persons' => [
                [
                    'id' => (string) $person1Id,
                    'name' => 'Michal',
                ],
                [
                    'id' => (string) $person2Id,
                    'name' => 'Vlado',
                ],
                [
                    'id' => (string) $person3Id,
                    'name' => 'Tibor',
                ],
            ],
            'skills' => [
                [
                    'id' => (string) $skill1Id,
                    'name' => 'Programming Language',
                ],
                [
                    'id' => (string) $skill2Id,
                    'name' => 'Database Concepts',
                ],
                [
                    'id' => (string) $skill3Id,
                    'name' => 'Debugging & Profiling',
                ],
                [
                    'id' => (string) $skill4Id,
                    'name' => 'Client-side Scripting',
                ],
            ],
        ];

        $response = $this->post('/skillmatrix', $skillmatrix);
        $responseBody = json_decode((string) $response->getBody(), true);
        $skillmatrixId = $responseBody['id'];

        // 2. Rate Person1 & Skill1
        $rating = [
            'personId' => (string) $person1Id,
            'skillId' => (string) $skill1Id,
            'reviewer' => [
                'id' => '93faef13-f879-4ff1-94f8-8396b780b31a',
                'name' => 'Reviewer 1',
            ],
            'score' => 3,
            'note' => 'Test rating 1',
        ];
        $response = $this->patch('/skillmatrix/' . $skillmatrixId . '/ratings', $rating);

        $testData = [
            'id' => $skillmatrixId,
            'persons' => $skillmatrix['persons'],
            'skills' => $skillmatrix['skills'],
            'ratings' => [
                $rating,
            ],
        ];

        $this->assertResponse(200, $testData, $response);

        $responseBody = json_decode((string) $response->getBody(), true);
        $this->assertArrayHasKey('created', $responseBody['ratings'][0]);
        $this->assertTrue(Assertion::date($responseBody['ratings'][0]['created'], DATE_ATOM));

        // 3. Check if Rating was persisted
        $response = $this->get('/skillmatrix/' . $skillmatrixId);
        $this->assertResponse(200, $testData, $response);
    }

    public function testRateForNonExistingSkillmatrix(): void
    {
        $skillmatrixId = SkillmatrixId::generate();

        // 2. Rate Person1 & Skill1
        $rating = [
            'personId' => (string) PersonId::generate(),
            'skillId' => (string) SkillId::generate(),
            'reviewer' => [
                'id' => '93faef13-f879-4ff1-94f8-8396b780b31a',
                'name' => 'Reviewer 1',
            ],
            'score' => 3,
            'note' => 'Test rating 1',
        ];
        $response = $this->patch('/skillmatrix/' . $skillmatrixId . '/ratings', $rating);
        $this->assertResponse(404, [
            'title' => 'Not Found',
            'status' => 404,
            'detail' => sprintf('Skillmatrix "%s" not found', $skillmatrixId),
        ], $response);
    }

    public function testRateAlreadyExistingRating(): void
    {
        $person1Id = PersonId::generate();
        $person2Id = PersonId::generate();
        $person3Id = PersonId::generate();
        $skill1Id = SkillId::generate();
        $skill2Id = SkillId::generate();
        $skill3Id = SkillId::generate();
        $skill4Id = SkillId::generate();

        // 1. Create Skillmatrix
        $skillmatrix = [
            'persons' => [
                [
                    'id' => (string) $person1Id,
                    'name' => 'Michal',
                ],
                [
                    'id' => (string) $person2Id,
                    'name' => 'Vlado',
                ],
                [
                    'id' => (string) $person3Id,
                    'name' => 'Tibor',
                ],
            ],
            'skills' => [
                [
                    'id' => (string) $skill1Id,
                    'name' => 'Programming Language',
                ],
                [
                    'id' => (string) $skill2Id,
                    'name' => 'Database Concepts',
                ],
                [
                    'id' => (string) $skill3Id,
                    'name' => 'Debugging & Profiling',
                ],
                [
                    'id' => (string) $skill4Id,
                    'name' => 'Client-side Scripting',
                ],
            ],
        ];

        $response = $this->post('/skillmatrix', $skillmatrix);
        $responseBody = json_decode((string) $response->getBody(), true);
        $skillmatrixId = $responseBody['id'];

        // 2. Rate Person1 & Skill1
        $rating = [
            'personId' => (string) $person1Id,
            'skillId' => (string) $skill1Id,
            'reviewer' => [
                'id' => '93faef13-f879-4ff1-94f8-8396b780b31a',
                'name' => 'Reviewer 1',
            ],
            'score' => 3,
            'note' => 'Test rating 1',
        ];
        $response = $this->patch('/skillmatrix/' . $skillmatrixId . '/ratings', $rating);

        // 3. Rate with different Reviewer but with the same Person & Skill
        $rating = [
            'personId' => (string) $person1Id,
            'skillId' => (string) $skill1Id,
            'reviewer' => [
                'id' => 'e328c92b-0845-4573-ad85-c6e038b92867',
                'name' => 'Reviewer 2',
            ],
            'score' => 4,
            'note' => 'Test rating 2',
        ];
        $response = $this->patch('/skillmatrix/' . $skillmatrixId . '/ratings', $rating);
        $this->assertResponse(409, [
            'title' => 'Conflict',
            'status' => 409,
            'detail' => sprintf(
                'Skillmatrix "%s" already contains rating for Person "%s" and Skill "%s"',
                $skillmatrixId,
                $person1Id,
                $skill1Id,
            ),
        ], $response);
    }
}
