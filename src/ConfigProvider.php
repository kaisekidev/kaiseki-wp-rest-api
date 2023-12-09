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
