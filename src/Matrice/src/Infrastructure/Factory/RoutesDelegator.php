<?php
declare(strict_types=1);

namespace Matrice\Infrastructure\Factory;

use Matrice\Action\CreateSkillmatrixAction;
use Matrice\Action\DisplaySkillmatrixAction;
use Matrice\Action\RatePersonAction;
use Mezzio\Application;
use Psr\Container\ContainerInterface;

final class RoutesDelegator
{
    public function __invoke(ContainerInterface $container, string $serviceName, callable $callback): Application
    {
        /** @var Application $app */
        $app = $callback();

        $app->get('/skillmatrix/{id}', DisplaySkillmatrixAction::class, 'skillmatrix');
        $app->post('/skillmatrix', CreateSkillmatrixAction::class, 'skillmatrix.create');
        $app->patch('/skillmatrix/{id}/ratings', RatePersonAction::class, 'skillmatrix.rating');

        return $app;
    }
}
