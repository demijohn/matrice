<?php
declare(strict_types=1);

namespace MatriceTest\Integration\Action;

use Matrice\Domain\Model\Skillmatrix\PersonId;
use Matrice\Domain\Model\Skillmatrix\SkillId;
use Matrice\Library\Test\TestCase;
use Mezzio\Application;

class CreateSkillmatrixActionTest extends TestCase
{
    public function setUp(): void
    {
        $container = require __DIR__ . '/../../container.php';
        $this->app = $container->get(Application::class);
    }

    public function testCreateSkillmatrix(): void
    {
        $skillmatrix = [
            'persons' => [
                [
                    'id' => (string) PersonId::generate(),
                    'name' => 'Michal',
                ],
                [
                    'id' => (string) PersonId::generate(),
                    'name' => 'Vlado',
                ],
                [
                    'id' => (string) PersonId::generate(),
                    'name' => 'Tibor',
                ],
            ],
            'skills' => [
                [
                    'id' => (string) SkillId::generate(),
                    'name' => 'Programming Language',
                ],
                [
                    'id' => (string) SkillId::generate(),
                    'name' => 'Database Concepts',
                ],
                [
                    'id' => (string) SkillId::generate(),
                    'name' => 'Debugging & Profiling',
                ],
                [
                    'id' => (string) SkillId::generate(),
                    'name' => 'Client-side Scripting',
                ],
            ],
        ];

        $response = $this->post('/skillmatrix', $skillmatrix);

        $this->assertResponse(201, $skillmatrix, $response);
    }

    public function testCreateSkillmatrixWithMissingParameters(): void
    {
        // Test missing all parameters
        $response = $this->post('/skillmatrix', []);

        $this->assertResponse(422, [
            'errors' => [
                '$' => [
                    0 => 'Array does not contain an element with key "persons"',
                    1 => 'Array does not contain an element with key "skills"',
                ],
                '$.persons' => [
                    0 => 'Value "<ARRAY>" is empty, but non empty value was expected.',
                ],
                '$.skills' => [
                    0 => 'Value "<ARRAY>" is empty, but non empty value was expected.',
                ],
            ],
            'title' => 'Request Params Validation Failed',
            'type' => 'https://httpstatus.es/422',
            'status' => 422,
            'detail' => 'The following 4 assertions failed:
1) $: Array does not contain an element with key "persons"
2) $: Array does not contain an element with key "skills"
3) $.persons: Value "<ARRAY>" is empty, but non empty value was expected.
4) $.skills: Value "<ARRAY>" is empty, but non empty value was expected.
',
        ], $response);

        // Test missing `persons` parameter
        $skillmatrix = [
            'skills' => [
                [
                    'id' => (string) SkillId::generate(),
                    'name' => 'Programming Language',
                ],
            ],
        ];

        $response = $this->post('/skillmatrix', $skillmatrix);

        $this->assertResponse(422, [
            'errors' => [
                '$' => [
                    0 => 'Array does not contain an element with key "persons"',
                ],
                '$.persons' => [
                    0 => 'Value "<ARRAY>" is empty, but non empty value was expected.',
                ],
            ],
            'title' => 'Request Params Validation Failed',
            'type' => 'https://httpstatus.es/422',
            'status' => 422,
            'detail' => 'The following 2 assertions failed:
1) $: Array does not contain an element with key "persons"
2) $.persons: Value "<ARRAY>" is empty, but non empty value was expected.
',
        ], $response);

        // Test missing `skills` parameter
        $skillmatrix = [
            'persons' => [
                [
                    'id' => (string) PersonId::generate(),
                    'name' => 'Michal',
                ],
            ],
        ];

        $response = $this->post('/skillmatrix', $skillmatrix);

        $this->assertResponse(422, [
            'errors' => [
                '$' => [
                    0 => 'Array does not contain an element with key "skills"',
                ],
                '$.skills' => [
                    0 => 'Value "<ARRAY>" is empty, but non empty value was expected.',
                ],
            ],
            'title' => 'Request Params Validation Failed',
            'type' => 'https://httpstatus.es/422',
            'status' => 422,
            'detail' => 'The following 2 assertions failed:
1) $: Array does not contain an element with key "skills"
2) $.skills: Value "<ARRAY>" is empty, but non empty value was expected.
',
        ], $response);

        // Test missing Person ID
        $skillmatrix = [
            'persons' => [
                [
                    'name' => 'Michal',
                ],
            ],
            'skills' => [
                [
                    'id' => (string) SkillId::generate(),
                    'name' => 'Programming Language',
                ],
            ],
        ];

        $response = $this->post('/skillmatrix', $skillmatrix);

        $this->assertResponse(422, [
            'errors' => [
                '$.person' => [
                    0 => 'Array does not contain an element with key "id"',
                ],
                '$.person.id' => [
                    0 => 'Value "" is not a valid UUID.',
                ],
            ],
            'title' => 'Request Params Validation Failed',
            'type' => 'https://httpstatus.es/422',
            'status' => 422,
            'detail' => 'The following 2 assertions failed:
1) $.person: Array does not contain an element with key "id"
2) $.person.id: Value "" is not a valid UUID.
',
        ], $response);

        // Test missing Person name
        $skillmatrix = [
            'persons' => [
                [
                    'id' => (string) PersonId::generate(),
                ],
            ],
            'skills' => [
                [
                    'id' => (string) SkillId::generate(),
                    'name' => 'Programming Language',
                ],
            ],
        ];

        $response = $this->post('/skillmatrix', $skillmatrix);

        $this->assertResponse(422, [
            'errors' => [
                '$.person' => [
                    0 => 'Array does not contain an element with key "name"',
                ],
                '$.person.name' => [
                    0 => 'Value "" is too short, it should have at least 1 characters, but only has 0 characters.',
                ],
            ],
            'title' => 'Request Params Validation Failed',
            'type' => 'https://httpstatus.es/422',
            'status' => 422,
            'detail' => 'The following 2 assertions failed:
1) $.person: Array does not contain an element with key "name"
2) $.person.name: Value "" is too short, it should have at least 1 characters, but only has 0 characters.
',
        ], $response);

        // Test missing Skill ID
        $skillmatrix = [
            'persons' => [
                [
                    'id' => (string) PersonId::generate(),
                    'name' => 'Michal',
                ],
            ],
            'skills' => [
                [
                    'name' => 'Programming Language',
                ],
            ],
        ];

        $response = $this->post('/skillmatrix', $skillmatrix);

        $this->assertResponse(422, [
            'errors' => [
                '$.skill' => [
                    0 => 'Array does not contain an element with key "id"',
                ],
                '$.skill.id' => [
                    0 => 'Value "" is not a valid UUID.',
                ],
            ],
            'title' => 'Request Params Validation Failed',
            'type' => 'https://httpstatus.es/422',
            'status' => 422,
            'detail' => 'The following 2 assertions failed:
1) $.skill: Array does not contain an element with key "id"
2) $.skill.id: Value "" is not a valid UUID.
',
        ], $response);

        // Test missing Skill name
        $skillmatrix = [
            'persons' => [
                [
                    'id' => (string) PersonId::generate(),
                    'name' => 'Michal',
                ],
            ],
            'skills' => [
                [
                    'id' => (string) SkillId::generate(),
                ],
            ],
        ];

        $response = $this->post('/skillmatrix', $skillmatrix);

        $this->assertResponse(422, [
            'errors' => [
                '$.skill' => [
                    0 => 'Array does not contain an element with key "name"',
                ],
                '$.skill.name' => [
                    0 => 'Value "" is too short, it should have at least 1 characters, but only has 0 characters.',
                ],
            ],
            'title' => 'Request Params Validation Failed',
            'type' => 'https://httpstatus.es/422',
            'status' => 422,
            'detail' => 'The following 2 assertions failed:
1) $.skill: Array does not contain an element with key "name"
2) $.skill.name: Value "" is too short, it should have at least 1 characters, but only has 0 characters.
',
        ], $response);
    }

    public function testCreateSkillmatrixWithInvalidParameters(): void
    {
        // Test invalid Person ID
        $skillmatrix = [
            'persons' => [
                [
                    'id' => 'invalid_person_id',
                    'name' => 'Michal',
                ],
            ],
            'skills' => [
                [
                    'id' => (string) SkillId::generate(),
                    'name' => 'Programming Language',
                ],
            ],
        ];

        $response = $this->post('/skillmatrix', $skillmatrix);

        $this->assertResponse(422, [
            'errors' => [
                '$.person.id' => [
                    0 => 'Value "invalid_person_id" is not a valid UUID.',
                ],
            ],
            'title' => 'Request Params Validation Failed',
            'type' => 'https://httpstatus.es/422',
            'status' => 422,
            'detail' => 'The following 1 assertions failed:
1) $.person.id: Value "invalid_person_id" is not a valid UUID.
',
        ], $response);

        // Test invalid Skill ID
        $skillmatrix = [
            'persons' => [
                [
                    'id' => (string) PersonId::generate(),
                    'name' => 'Michal',
                ],
            ],
            'skills' => [
                [
                    'id' => 'invalid_skill_id',
                    'name' => 'Programming Language',
                ],
            ],
        ];

        $response = $this->post('/skillmatrix', $skillmatrix);

        $this->assertResponse(422, [
            'errors' => [
                '$.skill.id' => [
                    0 => 'Value "invalid_skill_id" is not a valid UUID.',
                ],
            ],
            'title' => 'Request Params Validation Failed',
            'type' => 'https://httpstatus.es/422',
            'status' => 422,
            'detail' => 'The following 1 assertions failed:
1) $.skill.id: Value "invalid_skill_id" is not a valid UUID.
',
        ], $response);

        // Test invalid Person name
        $skillmatrix = [
            'persons' => [
                [
                    'id' => (string) PersonId::generate(),
                    'name' => 123,
                ],
            ],
            'skills' => [
                [
                    'id' => (string) SkillId::generate(),
                    'name' => 'Programming Language',
                ],
            ],
        ];

        $response = $this->post('/skillmatrix', $skillmatrix);

        $this->assertResponse(422, [
            'errors' => [
                '$.person.name' => [
                    0 => 'Value "123" expected to be string, type integer given.',
                ],
            ],
            'title' => 'Request Params Validation Failed',
            'type' => 'https://httpstatus.es/422',
            'status' => 422,
            'detail' => 'The following 1 assertions failed:
1) $.person.name: Value "123" expected to be string, type integer given.
',
        ], $response);

        // Test invalid Skill name
        $skillmatrix = [
            'persons' => [
                [
                    'id' => (string) PersonId::generate(),
                    'name' => 'Michal',
                ],
            ],
            'skills' => [
                [
                    'id' => (string) SkillId::generate(),
                    'name' => 123,
                ],
            ],
        ];

        $response = $this->post('/skillmatrix', $skillmatrix);

        $this->assertResponse(422, [
            'errors' => [
                '$.skill.name' => [
                    0 => 'Value "123" expected to be string, type integer given.',
                ],
            ],
            'title' => 'Request Params Validation Failed',
            'type' => 'https://httpstatus.es/422',
            'status' => 422,
            'detail' => 'The following 1 assertions failed:
1) $.skill.name: Value "123" expected to be string, type integer given.
',
        ], $response);
    }
}
