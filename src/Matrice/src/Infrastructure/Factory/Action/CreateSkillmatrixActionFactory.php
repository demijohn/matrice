<?php
declare(strict_types=1);

namespace Matrice\Infrastructure\Factory\Action;

use Matrice\Action\CreateSkillmatrixAction;
use Matrice\Domain\Model\Skillmatrix\SkillmatrixRepository;
use Psr\Container\ContainerInterface;

final class CreateSkillmatrixActionFactory
{
    public function __invoke(ContainerInterface $container): CreateSkillmatrixAction
    {
        return new CreateSkillmatrixAction(
            $container->get('command_bus.default'),
            $container->get(SkillmatrixRepository::class)
        );
    }
}
