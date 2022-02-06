<?php

declare(strict_types=1);

namespace Kaiseki\WordPress\RestApi;

final class ConfigProvider
{
    /**
     * @return array<mixed>
     */
    public function __invoke(): array
    {
        return [
            'rest_api' => [
                'handler' => [],
            ],
            'hook' => [
                'provider' => [
                    RouteRegistry::class,
                ],
            ],
            'dependencies' => [
                'aliases' => [],
                'factories' => [
                    RouteRegistry::class => RouteRegistryFactory::class,
                ],
            ],
        ];
    }
}
