<?php
declare(strict_types=1);

namespace Matrice\Infrastructure\Factory;

use League\Tactician\CommandBus;
use League\Tactician\Container\ContainerLocator;
use League\Tactician\Doctrine\ORM\TransactionMiddleware;
use League\Tactician\Handler\CommandHandlerMiddleware;
use League\Tactician\Handler\CommandNameExtractor\ClassNameExtractor;
use League\Tactician\Handler\MethodNameInflector\HandleInflector;
use League\Tactician\Plugins\LockingMiddleware;
use Psr\Container\ContainerInterface;

final class CommandBusFactory
{
    public function __invoke(ContainerInterface $container): CommandBus
    {
        $config = $container->get('config');

        if (!isset($config['command_bus'])) {
            throw new \RuntimeException('Command bus configuration missing');
        }

        $handlers = $config['command_bus']['handlers'];

        $handlerMiddleware = new CommandHandlerMiddleware(
            new ClassNameExtractor(),
            new ContainerLocator(
                $container,
                $handlers,
            ),
            new HandleInflector(),
        );

        $lockingMiddleware = new LockingMiddleware();

        $transactionMiddleware = new TransactionMiddleware(
            $container->get('doctrine.entity_manager.orm_default'),
        );

        return new CommandBus([
            $lockingMiddleware,
            $transactionMiddleware,
            $handlerMiddleware,
        ]);
    }
}
