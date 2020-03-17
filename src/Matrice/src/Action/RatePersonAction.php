<?php
declare(strict_types=1);

namespace Matrice\Action;

use Assert\Assert;
use League\Tactician\CommandBus;
use Matrice\Application\Command\RatePerson;
use Matrice\Domain\Model\Skillmatrix\SkillmatrixId;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class RatePersonAction implements RequestHandlerInterface
{
    private CommandBus $commandBus;

    private DisplaySkillmatrixAction $displayAction;

    public function __construct(CommandBus $commandBus, DisplaySkillmatrixAction $displayAction)
    {
        $this->commandBus = $commandBus;
        $this->displayAction = $displayAction;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $skillmatrixIdParameter = $request->getAttribute('id');

        /** @var array $data */
        $data = $request->getParsedBody() ?? [];

        Assert::lazy()
            ->that($data, '$')->tryAll()
            ->keyExists('personId')->keyExists('skillId')->keyExists('reviewer')
            ->that($data['personId'] ?? null, '$.personId')->uuid()
            ->that($data['skillId'] ?? null, '$.skillId')->uuid()
            ->that($data['reviewer'] ?? [], '$.reviewer')->isArray()
            ->that($data['reviewer'], '$.reviewer')->tryAll()->keyExists('id')->keyExists('name')
            ->that($data['reviewer']['id'] ?? null, '$.reviewer.id')->uuid()
            ->that($data['reviewer']['name'] ?? '', '$.reviewer.name')->string()->minLength(1)
            ->that($skillmatrixIdParameter, 'id')->uuid()
            ->verifyNow();

        $skillmatrixId = SkillmatrixId::fromString($skillmatrixIdParameter);
        $command = RatePerson::fromArray($skillmatrixId, $data);

        $this->commandBus->handle($command);

        return $this->displayAction->handle($request);
    }
}
