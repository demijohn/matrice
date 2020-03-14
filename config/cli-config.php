<?php
declare(strict_types=1);

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Console\ConsoleRunner;
use Symfony\Component\Console\Helper\HelperSet;

require 'vendor/autoload.php';

/**
 * Self-called anonymous function that creates its own scope and keep the global namespace clean.
 */
return (function (): HelperSet {
    /** @var \Psr\Container\ContainerInterface $container */
    $container = require 'config/container.php';

    /** @var EntityManager $entityManager */
    $entityManager = $container->get('doctrine.entity_manager.orm_default');

    return ConsoleRunner::createHelperSet($entityManager);
})();
