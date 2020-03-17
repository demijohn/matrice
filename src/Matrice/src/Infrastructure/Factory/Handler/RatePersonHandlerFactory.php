<?php
declare(strict_types=1);

namespace Matrice\Infrastructure\Factory\Handler;

use Matrice\Application\Handler\RatePersonHandler;
use Matrice\Domain\Model\Skillmatrix\SkillmatrixRepository;
use Psr\Container\ContainerInterface;

final class RatePersonHandlerFactory
{
    public function __invoke(ContainerInterface $container): RatePersonHandler
    {
        return new RatePersonHandler(
            $container->get(SkillmatrixRepository::class),
        );
    }
}
