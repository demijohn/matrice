<?php
declare(strict_types=1);

namespace Matrice;

use ContainerInteropDoctrine\ConnectionFactory;
use ContainerInteropDoctrine\EntityManagerFactory;
use Doctrine\Common\Persistence\Mapping\Driver\MappingDriverChain;
use Doctrine\DBAL;
use Doctrine\ORM\Mapping;
use Matrice\Application\Command;
use Matrice\Application\Handler;
use Matrice\Domain\Model\Skillmatrix;
use Matrice\Infrastructure\Doctrine\Type;
use Matrice\Infrastructure\Factory;
use Matrice\Infrastructure\Factory\Repository;

final class ConfigProvider
{
    public function __invoke(): array
    {
        return [
            'dependencies' => $this->getDependencies(),
            'templates' => $this->getTemplates(),
            'doctrine' => $this->getDoctrine(),
            'command_bus' => $this->getCommandBus(),
        ];
    }

    private function getDependencies(): array
    {
        return [
            'invokables' => [
                Mapping\UnderscoreNamingStrategy::class => Mapping\UnderscoreNamingStrategy::class,
            ],
            'factories'  => [
                'command_bus.default' => Factory\CommandBusFactory::class,

                'doctrine.connection.orm_default' => ConnectionFactory::class,
                'doctrine.entity_manager.orm_default' => EntityManagerFactory::class,

                Action\CreateSkillmatrixAction::class => Factory\Action\CreateSkillmatrixActionFactory::class,
                Action\DisplaySkillmatrixAction::class => Factory\Action\DisplaySkillmatrixActionFactory::class,

                Handler\CreateSkillmatrixHandler::class => Factory\Handler\CreateSkillmatrixHandlerFactory::class,

                Skillmatrix\SkillmatrixRepository::class => Repository\DoctrineSkillmatrixRepositoryFactory::class,
            ],
        ];
    }

    private function getTemplates(): array
    {
        return [
            'paths' => [
                'app'    => [__DIR__ . '/../templates/app'],
                'error'  => [__DIR__ . '/../templates/error'],
                'layout' => [__DIR__ . '/../templates/layout'],
            ],
        ];
    }

    private function getDoctrine(): array
    {
        return [
            'connection' => [
                'orm_default' => [
                    'driver_class' => DBAL\Driver\PDOMySql\Driver::class,
                    'params' => [
                        'host' => getenv('MYSQL_HOST'),
                        'user' => getenv('MYSQL_USER'),
                        'password' => getenv('MYSQL_PASSWORD'),
                        'serverVersion' => '8.0.18',
                        'dbname' => 'matrice',
                        'charset' => 'utf8mb4',
                        'driverOptions' => [
                            'x_reconnect_attempts' => 1, //Number of reconnects per execute. Default value is 0
                        ],
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
                Type\PersonIdType::NAME => Type\PersonIdType::class,
                Type\ReviewerIdType::NAME => Type\ReviewerIdType::class,
                Type\SkillIdType::NAME => Type\SkillIdType::class,
            ],
        ];
    }

    private function getCommandBus(): array
    {
        return [
            'handlers' => [
                Command\CreateSkillmatrix::class => Handler\CreateSkillmatrixHandler::class,
            ],
        ];
    }
}
