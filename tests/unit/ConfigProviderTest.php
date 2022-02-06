<?php

declare(strict_types=1);

namespace Kaiseki\Test\Unit\WordPress\RestApi;

use Kaiseki\WordPress\RestApi\ConfigProvider;
use Kaiseki\WordPress\RestApi\RouteRegistry;
use Kaiseki\WordPress\RestApi\RouteRegistryFactory;
use PHPUnit\Framework\TestCase;

class ConfigProviderTest extends TestCase
{
    public function testConfig(): void
    {
        $config = (new ConfigProvider())();

        self::assertSame([
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
            ],
            $config
        );
    }
}
