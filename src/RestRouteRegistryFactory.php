<?php

declare(strict_types=1);

namespace Kaiseki\WordPress\RestApi;

use Kaiseki\Config\Config;
use Psr\Container\ContainerInterface;
use WP_REST_Server;

use function class_exists;

/**
 * @phpstan-type RestRouteConfig array{
 *     methods?: string|list<string>,
 *     callback: class-string<RestRouteCallbackInterface>,
 *     permission_callback: string|class-string<RestRoutePermissionCallbackInterface>,
 *     args?: array<string, mixed>,
 *     namespace?: string,
 *  }
 */
final class RestRouteRegistryFactory
{
    public function __invoke(ContainerInterface $container, RestRouteBuilder $builder): RestRouteRegistry
    {
        $config = Config::get($container);

        /** @var list<class-string<RestRouteInterface>> $routeClassStrings */
        $routeClassStrings = $config->array('rest_api/routes');
        /** @var list<RestRouteInterface> $routeClassStrings */
        $routes = Config::initClassMap($container, $routeClassStrings);

        /** @var array<string, list<RestRouteConfig>|RestRouteConfig> $routeConfigs */
        $routeConfigs = $config->array('rest_api/route_configs');
        foreach ($routeConfigs as $route => $routeConfig) {
            /** @var list<RestRouteConfig> $configList */
            $configList = isset($routeConfig['callback']) ? [$routeConfig] : $routeConfig;
            foreach ($configList as $configEntry) {
                /** @var RestRouteCallbackInterface $callback */
                $callback = $container->get($configEntry['callback']);

                $routes[] = $builder->fromConfig($route, [
                    'methods' => $configEntry['methods'] ?? WP_REST_Server::READABLE,
                    'callback' => $callback,
                    'permission_callback' => $this->getPermissionCallback(
                        $container,
                        $configEntry['permission_callback']
                    ),
                    'args' => $configEntry['args'] ?? [],
                    'namespace' => $configEntry['namespace'] ?? null,
                ]);
            }
        }

        return new RestRouteRegistry(
            $config->string('api/rest_namespace'),
            $routes
        );
    }

    private function getPermissionCallback(
        ContainerInterface $container,
        string $permissionCallback
    ): RestRoutePermissionCallbackInterface|string {
        if (!class_exists($permissionCallback)) {
            return $permissionCallback;
        }

        $callback = $container->get($permissionCallback);

        if (!$callback instanceof RestRoutePermissionCallbackInterface) {
            throw new \InvalidArgumentException(
                sprintf(
                    'Permission callback %s must implement %s',
                    $permissionCallback,
                    RestRoutePermissionCallbackInterface::class
                )
            );
        }

        return $callback;
    }
}
