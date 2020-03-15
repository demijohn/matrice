<?php
declare(strict_types=1);

namespace Matrice\Infrastructure\Factory\Action;

use Matrice\Action\DisplaySkillmatrixAction;
use Matrice\Domain\Model\Skillmatrix\SkillmatrixRepository;
use Mezzio\Hal\HalResponseFactory;
use Mezzio\Hal\ResourceGenerator;
use Psr\Container\ContainerInterface;

final class DisplaySkillmatrixActionFactory
{
    public function __invoke(ContainerInterface $container): DisplaySkillmatrixAction
    {
        return new DisplaySkillmatrixAction(
            $container->get(SkillmatrixRepository::class),
            $container->get(ResourceGenerator::class),
            $container->get(HalResponseFactory::class),
        );
    }
}
