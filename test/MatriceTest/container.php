<?php
declare(strict_types=1);

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;
use Laminas\ServiceManager\ServiceManager;
use Laminas\Stdlib\ArrayUtils;

$appConfig = require __DIR__ . '/../../config/config.php';
$testConfig = require __DIR__ . '/config/test.config.php';

$config = ArrayUtils::merge($appConfig, $testConfig);

$dependencies = $config['dependencies'];
$dependencies['services']['config'] = $config;

// Build container
$container = new ServiceManager($dependencies);

/** @var EntityManagerInterface $em */
$em = $container->get('doctrine.entity_manager.orm_default');

$metadatas = $em->getMetadataFactory()->getAllMetadata();

$tool = new SchemaTool($em);
$tool->createSchema($metadatas);

return $container;
