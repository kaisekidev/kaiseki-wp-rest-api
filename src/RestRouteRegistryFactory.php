<?php

declare(strict_types=1);

namespace Kaiseki\WordPress\RestApi;

use Kaiseki\Config\Config;
use Psr\Container\ContainerInterface;
use WP_REST_Server;

use function class_exists;
use function function_exists;

/**
 * @phpstan-type RestRouteConfig array{
 *     methods?: string|list<string>,
 *     callback: class-string<RestRouteCallbackInterface>,
 *     permission_callback: class-string<RestRoutePermissionCallbackInterface>|callable-string,
 *     args?: array<string, mixed>,
 *     namespace?: string,
 *  }
 */
final class RestRouteRegistryFactory
{
    public function __invoke(ContainerInterface $container): RestRouteRegistry
    {
        $config = Config::get($container);
        $builder = $container->get(RestRouteBuilder::class);

        /** @var list<class-string<RestRouteInterface>> $routeClassStrings */
        $routeClassStrings = $config->array('rest_api/routes', []);
        /** @var list<RestRouteInterface> $routeClassStrings */
        $routes = Config::initClassMap($container, $routeClassStrings);

        /** @var array<string, list<RestRouteConfig>|RestRouteConfig> $routeConfigs */
        $routeConfigs = $config->array('rest_api/route_configs', []);
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
            $config->string('rest_api/namespace'),
            $routes
        );
    }

    /**
     * @param ContainerInterface $container
     * @param string             $permissionCallback
     *
     * @return callable-string|RestRoutePermissionCallbackInterface
     */
    private function getPermissionCallback(
        ContainerInterface $container,
        string $permissionCallback
    ): RestRoutePermissionCallbackInterface|string {
        if (!class_exists($permissionCallback)) {
            return $this->getFunction($permissionCallback);
        }

        /** @var RestRoutePermissionCallbackInterface $callback */
        $callback = $container->get($permissionCallback);

        if (!$callback instanceof RestRoutePermissionCallbackInterface) {
            throw new \InvalidArgumentException(
                \Safe\sprintf(
                    'Permission callback %s must implement %s',
                    $permissionCallback,
                    RestRoutePermissionCallbackInterface::class
                )
            );
        }

        return $callback;
    }

    /**
     * @param string $function
     *
     * @return callable-string
     */
    private function getFunction(string $function): string
    {
        if (!function_exists($function)) {
            throw new \InvalidArgumentException(
                \Safe\sprintf(
                    'Function %s does not exist',
                    $function
                )
            );
        }

        return $function;
    }
}
