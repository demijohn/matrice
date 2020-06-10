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
            ->that($data['persons'] ?? [], '$.persons')->isArray()
            ->that($data['skills'] ?? [], '$.skills')->isArray()
            ->verifyNow();

        $skillmatrixId = $this->skillmatrixRepository->nextIdentity();

        $persons = PersonCollection::fromArray($data['persons']);
        $skills = SkillCollection::fromArray($data['skills']);

        $command = CreateSkillmatrix::create($skillmatrixId, $persons, $skills);
        $this->commandBus->handle($command);

        $displayRequest = $request->withAttribute('id', (string) $command->getId());

        return $this->displayAction->handle($displayRequest)->withStatus(201);
    }
}
