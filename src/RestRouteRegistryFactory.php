<?php

declare(strict_types=1);

namespace Kaiseki\WordPress\RestApi;

use Kaiseki\Config\Config;
use Psr\Container\ContainerInterface;
use WP_REST_Server;

/**
 * @phpstan-type RestRouteConfig array{
 *     methods?: string|list<string>,
 *     callback: class-string<RestRouteCallbackInterface>,
 *     permission_callback: class-string<RestRoutePermissionCallbackInterface>,
 *     args?: array<string, mixed>,
 *     namespace?: string,
 *  }
 */
final class RestRouteRegistryFactory
{
    public function __invoke(ContainerInterface $container, RestRouteBuilder $builder): RestRouteRegistry
    {
        $config = Config::build((array)$container->get('config'), '.');

        /** @var list<class-string<RestRouteInterface>> $routeClassStrings */
        $routeClassStrings = $config->array('rest_api.routes');
        /** @var list<RestRouteInterface> $routeClassStrings */
        $routes = Config::initClassMap($container, $routeClassStrings);

        /** @var array<string, list<RestRouteConfig>|RestRouteConfig> $routeConfigs */
        $routeConfigs = $config->array('rest_api.route_configs');
        foreach ($routeConfigs as $route => $routeConfig) {
            /** @var list<RestRouteConfig> $configList */
            $configList = isset($routeConfig['callback']) ? [$routeConfig] : $routeConfig;
            foreach ($configList as $configEntry) {
                /** @var RestRouteCallbackInterface $callback */
                $callback = $container->get($configEntry['callback']);
                /** @var RestRoutePermissionCallbackInterface $permissionCallback */
                $permissionCallback = $container->get($configEntry['permission_callback']);

                $routes[] = $builder->fromConfig($route, [
                    'methods' => $configEntry['methods'] ?? WP_REST_Server::READABLE,
                    'callback' => $callback,
                    'permission_callback' => $permissionCallback,
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
}
