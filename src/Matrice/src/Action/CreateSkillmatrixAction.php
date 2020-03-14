<?php
declare(strict_types=1);

namespace Matrice\Action;

use Assert\Assert;
use Laminas\Diactoros\Response\TextResponse;
use League\Tactician\CommandBus;
use Matrice\Application\Command\CreateSkillmatrix;
use Matrice\Domain\Model\Skillmatrix\PersonCollection;
use Matrice\Domain\Model\Skillmatrix\SkillCollection;
use Matrice\Domain\Model\Skillmatrix\SkillmatrixRepository;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class CreateSkillmatrixAction implements RequestHandlerInterface
{
    private CommandBus $commandBus;

    private SkillmatrixRepository $skillmatrixRepository;

    public function __construct(CommandBus $commandBus, SkillmatrixRepository $skillmatrixRepository)
    {
        $this->commandBus = $commandBus;
        $this->skillmatrixRepository = $skillmatrixRepository;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        /** @var array $data */
        $data = $request->getParsedBody() ?? [];

        Assert::lazy()
            ->that($data, '$')->tryAll()
            ->keyExists('persons')->keyExists('skills')
            ->that($data['persons'] ?? [], '$.persons')->isArray()
            ->that($data['skills'] ?? [], '$.skills')->isArray()
            ->verifyNow();

        $skillmatrixId = $this->skillmatrixRepository->nextIdentity();

        $persons = PersonCollection::jsonDeserialize($data['persons']);
        $skills = SkillCollection::jsonDeserialize($data['skills']);

        $command = CreateSkillmatrix::create($skillmatrixId, $persons, $skills);
        $this->commandBus->handle($command);

        return new TextResponse('OK BRO');
    }
}
