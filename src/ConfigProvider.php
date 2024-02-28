<?php

declare(strict_types=1);

namespace Kaiseki\WordPress\RestApi;

final class ConfigProvider
{
    /**
     * @return array<array-key, mixed>
     */
    public function __invoke(): array
    {
        return [
            'rest_api' => [
                'namespace' => '',
                'routes' => [],
                'route_configs' => [],
            ],
            'hook' => [
                'provider' => [
                    RestRouteRegistry::class,
                ],
            ],
            'dependencies' => [
                'aliases' => [],
                'factories' => [
                    RestRouteRegistry::class => RestRouteRegistryFactory::class,
                ],
            ],
        ];
    }
}
