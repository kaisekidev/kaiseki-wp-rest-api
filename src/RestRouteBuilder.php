<?php

declare(strict_types=1);

namespace Kaiseki\WordPress\RestApi;

use WP_REST_Server;

/**
 * @phpstan-type RestRouteConfig array{
 *    methods?: string|list<string>|null,
 *    callback: RestRouteCallbackInterface,
 *    permission_callback: RestRoutePermissionCallbackInterface,
 *    args?: array<string, mixed>|null,
 *    namespace?: string|null,
 * }
 */
class RestRouteBuilder
{
    /**
     * @param string          $route
     * @param RestRouteConfig $config
     *
     * @return RestRouteInterface
     */
    public function fromConfig(string $route, array $config): RestRouteInterface
    {
        $methods = $config['methods'] ?? WP_REST_Server::READABLE;
        $callback = $config['callback'];
        $permissionCallback = $config['permission_callback'];
        $arguments = $config['args'] ?? [];
        $namespace = $config['namespace'] ?? null;

        return new RestRoute($route, $methods, $callback, $permissionCallback, $arguments, $namespace);
    }
}
