<?php
declare(strict_types=1);

namespace Matrice;

use Doctrine\Common\Persistence\Mapping\Driver\MappingDriverChain;
use Doctrine\DBAL;
use Doctrine\ORM\Mapping;
use Matrice\Application\Command;
use Matrice\Application\Handler;
use Matrice\Domain\Model\Skillmatrix;
use Matrice\Infrastructure\Doctrine\Type;
use Matrice\Infrastructure\Factory;
use Matrice\Infrastructure\Factory\Repository;
use Mezzio\Application;
use Roave\PsrContainerDoctrine\ConnectionFactory;
use Roave\PsrContainerDoctrine\EntityManagerFactory;

final class ConfigProvider
{
    public function __invoke(): array
    {
        return [
            'parameters' => $this->getParameters(),
            'dependencies' => $this->getDependencies(),
            'templates' => $this->getTemplates(),
            'doctrine' => $this->getDoctrine(),
            'command_bus' => $this->getCommandBus(),
        ];
    }

    private function getParameters(): array
    {
        return [
            'db' => 'mysql',
        ];
    }

    private function getDependencies(): array
    {
        return [
            'aliases' => [
                'doctrine.connection.orm_default' => 'doctrine.connection.' . (getenv('DB') ?: '%db%'),
            ],
            'delegators' => [
                Application::class => [
                    Factory\PipelineDelegator::class,
                    Factory\RoutesDelegator::class,
                ],
            ],
            'invokables' => [
                Mapping\UnderscoreNamingStrategy::class => Mapping\UnderscoreNamingStrategy::class,
            ],
            'factories' => [
                'command_bus.default' => Factory\CommandBusFactory::class,

                'doctrine.connection.mysql' => [ConnectionFactory::class, 'mysql'],
                'doctrine.connection.sqlite' => [ConnectionFactory::class, 'sqlite'],
                'doctrine.entity_manager.orm_default' => EntityManagerFactory::class,

                Action\CreateSkillmatrixAction::class => Factory\Action\CreateSkillmatrixActionFactory::class,
                Action\DisplaySkillmatrixAction::class => Factory\Action\DisplaySkillmatrixActionFactory::class,
                Action\RatePersonAction::class => Factory\Action\RatePersonActionFactory::class,

                Handler\CreateSkillmatrixHandler::class => Factory\Handler\CreateSkillmatrixHandlerFactory::class,
                Handler\RatePersonHandler::class => Factory\Handler\RatePersonHandlerFactory::class,

                Skillmatrix\SkillmatrixRepository::class => Repository\DoctrineSkillmatrixRepositoryFactory::class,
            ],
        ];
    }

    private function getTemplates(): array
    {
        return [
            'paths' => [
                'app' => [__DIR__ . '/../templates/app'],
                'error' => [__DIR__ . '/../templates/error'],
                'layout' => [__DIR__ . '/../templates/layout'],
            ],
        ];
    }

    private function getDoctrine(): array
    {
        return [
            'connection' => [
                'mysql' => [
                    'driver_class' => DBAL\Driver\PDOMySql\Driver::class,
                    'configuration' => 'orm_default',
                    'params' => [
                        'host' => getenv('MYSQL_HOST'),
                        'user' => getenv('MYSQL_USER'),
                        'password' => getenv('MYSQL_PASSWORD'),
                        'serverVersion' => '8.0.18',
                        'dbname' => 'matrice',
                        'charset' => 'utf8mb4',
                        'driverOptions' => [
                            //Number of reconnects per execute. Default value is 0
                            'x_reconnect_attempts' => 1,
                        ],
                    ],
                ],
                'sqlite' => [
                    'driver_class' => DBAL\Driver\PDOSqlite\Driver::class,
                    'configuration' => 'test',
                    'params' => [
                        'memory' => true,
                        'path' => getenv('SQLITE_PATH') ?: null,
                    ],
                ],
            ],

            'configuration' => [
                'orm_default' => [
                    'metadata_cache' => 'array',
                    'query_cache' => 'array',

                    'result_cache' => 'array',
                    'hydration_cache' => 'array',

                    'auto_generate_proxy_classes' => false,

                    'naming_strategy' => Mapping\UnderscoreNamingStrategy::class,

                    'second_level_cache' => [
                        'enabled' => true,
                        'default_lifetime' => 3_600,
                        'default_lock_lifetime' => 60,
                        'file_lock_region_directory' => '',
                        'regions' => [],
                    ],
                ],

                'test' => [
                    'metadata_cache' => 'array',
                    'query_cache' => 'array',
                    'result_cache' => 'array',
                    'hydration_cache' => 'array',

                    'driver' => 'orm_default',
                    'auto_generate_proxy_classes' => true,
                    'second_level_cache' => [
                        'enabled' => false,
                    ],
                ],
            ],

            'driver' => [
                'orm_default' => [
                    'class' => MappingDriverChain::class,
                    'drivers' => [
                        'Matrice\Domain\Model' => 'matrice_model',
                    ],
                ],

                'matrice_model' => [
                    'class' => Mapping\Driver\AnnotationDriver::class,
                    'cache' => 'array',
                    'paths' => __DIR__ . '/Domain/Model',
                ],
            ],

            'types' => [
                Type\PersonCollectionType::NAME => Type\PersonCollectionType::class,
                Type\SkillCollectionType::NAME => Type\SkillCollectionType::class,
                Type\RatingCollectionType::NAME => Type\RatingCollectionType::class,
                Type\SkillmatrixIdType::NAME => Type\SkillmatrixIdType::class,
            ],
        ];
    }

    private function getCommandBus(): array
    {
        return [
            'handlers' => [
                Command\CreateSkillmatrix::class => Handler\CreateSkillmatrixHandler::class,
                Command\RatePerson::class => Handler\RatePersonHandler::class,
            ],
        ];
    }
}
