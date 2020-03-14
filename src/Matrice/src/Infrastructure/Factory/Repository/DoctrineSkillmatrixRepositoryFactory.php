<?php
declare(strict_types=1);

namespace Matrice\Infrastructure\Factory\Repository;

use Matrice\Infrastructure\Doctrine\Repository\DoctrineSkillmatrixRepository;
use Psr\Container\ContainerInterface;

final class DoctrineSkillmatrixRepositoryFactory
{
    public function __invoke(ContainerInterface $container): DoctrineSkillmatrixRepository
    {
        return new DoctrineSkillmatrixRepository(
            $container->get('doctrine.entity_manager.orm_default'),
        );
    }
}
