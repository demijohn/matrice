<?php
declare(strict_types=1);

use Laminas\Hydrator\ArraySerializableHydrator;
use Matrice\Domain\Model\Skillmatrix\Skillmatrix;
use Mezzio\Hal\Metadata\MetadataMap;
use Mezzio\Hal\Metadata\RouteBasedResourceMetadata;

return [
    MetadataMap::class => [
        [
            '__class__' => RouteBasedResourceMetadata::class,
            'resource_class' => Skillmatrix::class,
            'route' => 'skillmatrix',
            'extractor' => ArraySerializableHydrator::class,
        ],
    ],
];
