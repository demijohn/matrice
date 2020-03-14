<?php
declare(strict_types=1);

namespace Matrice\Infrastructure\Factory\Handler;

use Matrice\Application\Handler\CreateSkillmatrixHandler;
use Matrice\Domain\Model\Skillmatrix\SkillmatrixRepository;
use Psr\Container\ContainerInterface;

final class CreateSkillmatrixHandlerFactory
{
    public function __invoke(ContainerInterface $container): CreateSkillmatrixHandler
    {
        return new CreateSkillmatrixHandler(
            $container->get(SkillmatrixRepository::class),
        );
    }
}
