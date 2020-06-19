<?php
declare(strict_types=1);

namespace Matrice\Library\Config;

use Laminas\ConfigAggregatorParameters\ParameterPostProcessor;

final class ConfigParameterPostProcessor
{
    public function __invoke(array $config): array
    {
        $parameters = $config['parameters'] ?? [];

        $processor = new ParameterPostProcessor($parameters);

        return $processor($config);
    }
}
