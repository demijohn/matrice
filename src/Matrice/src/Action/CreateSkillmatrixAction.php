<?php
declare(strict_types=1);

namespace Matrice\Action;

use League\Tactician\CommandBus;
use Matrice\Application\Command\CreateSkillmatrix;
use Matrice\Domain\Model\Skillmatrix\PersonCollection;
use Matrice\Domain\Model\Skillmatrix\SkillCollection;
use Matrice\Domain\Model\Skillmatrix\SkillmatrixRepository;
use Matrice\Library\Validation\Validate;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class CreateSkillmatrixAction implements RequestHandlerInterface
{
    private CommandBus $commandBus;

    private SkillmatrixRepository $skillmatrixRepository;

    private DisplaySkillmatrixAction $displayAction;

    public function __construct(
        CommandBus $commandBus,
        SkillmatrixRepository $skillmatrixRepository,
        DisplaySkillmatrixAction $displayAction
    ) {
        $this->commandBus = $commandBus;
        $this->skillmatrixRepository = $skillmatrixRepository;
        $this->displayAction = $displayAction;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        /** @var array $data */
        $data = $request->getParsedBody() ?? [];

        Validate::lazy()
            ->that($data, '$')->tryAll()
            ->keyExists('persons')->keyExists('skills')
            ->that($data['persons'] ?? [], '$.persons')->isArray()->notEmpty()
            ->that($data['skills'] ?? [], '$.skills')->isArray()->notEmpty()
            ->verifyNow();

        foreach ($data['persons'] as $person) {
            Validate::lazy()
                ->that($person, '$.person')->tryAll()
                ->keyExists('id')->keyExists('name')
                ->that($person['id'] ?? null, '$.person.id')->uuid()
                ->that($person['name'] ?? '', '$.person.name')->minLength(1)
                ->verifyNow();
        }

        foreach ($data['skills'] as $skill) {
            Validate::lazy()
                ->that($skill, '$.skill')->tryAll()
                ->keyExists('id')->keyExists('name')
                ->that($skill['id'] ?? null, '$.skill.id')->uuid()
                ->that($skill['name'] ?? '', '$.skill.name')->minLength(1)
                ->verifyNow();
        }

        $skillmatrixId = $this->skillmatrixRepository->nextIdentity();

        $persons = PersonCollection::fromArray($data['persons']);
        $skills = SkillCollection::fromArray($data['skills']);

        $command = new CreateSkillmatrix($skillmatrixId, $persons, $skills);
        $this->commandBus->handle($command);

        $displayRequest = $request->withAttribute('id', (string) $command->getId());

        return $this->displayAction->handle($displayRequest)->withStatus(201);
    }
}
