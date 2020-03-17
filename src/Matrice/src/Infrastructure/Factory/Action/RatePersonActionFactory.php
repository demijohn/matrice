<?php
declare(strict_types=1);

namespace Matrice\Infrastructure\Factory\Action;

use Matrice\Action\DisplaySkillmatrixAction;
use Matrice\Action\RatePersonAction;
use Psr\Container\ContainerInterface;

final class RatePersonActionFactory
{
    public function __invoke(ContainerInterface $container): RatePersonAction
    {
        return new RatePersonAction(
            $container->get('command_bus.default'),
            $container->get(DisplaySkillmatrixAction::class),
        );
    }
}
