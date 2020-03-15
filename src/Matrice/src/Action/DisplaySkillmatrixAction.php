<?php
declare(strict_types=1);

namespace Matrice\Action;

use Matrice\Domain\Model\Skillmatrix\SkillmatrixId;
use Matrice\Domain\Model\Skillmatrix\SkillmatrixRepository;
use Mezzio\Hal\HalResponseFactory;
use Mezzio\Hal\ResourceGenerator;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class DisplaySkillmatrixAction implements RequestHandlerInterface
{
    private SkillmatrixRepository $skillmatrixRepository;

    private ResourceGenerator $resourceGenerator;

    private HalResponseFactory $responseFactory;

    public function __construct(
        SkillmatrixRepository $skillmatrixRepository,
        ResourceGenerator $resourceGenerator,
        HalResponseFactory $responseFactory
    ) {
        $this->skillmatrixRepository = $skillmatrixRepository;
        $this->resourceGenerator = $resourceGenerator;
        $this->responseFactory = $responseFactory;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $skillmatrixId = SkillmatrixId::fromString($request->getAttribute('id'));

        $skillmatrix = $this->skillmatrixRepository->get($skillmatrixId);

        $resource = $this->resourceGenerator->fromObject($skillmatrix, $request);

        return $this->responseFactory->createResponse($request, $resource);
    }
}
