<?php

declare(strict_types=1);

namespace Kaiseki\WordPress\RestApi;

use Kaiseki\WordPress\Hook\HookCallbackProviderInterface;

final class RestRouteRegistry implements HookCallbackProviderInterface
{
    /**
     * @param string                   $namespace
     * @param list<RestRouteInterface> $routes
     */
    public function __construct(
        private readonly string $namespace,
        private readonly array $routes = [],
    ) {
    }

    public function registerHookCallbacks(): void
    {
        add_action('rest_api_init', [$this, 'registerRestEndpoint']);
    }

    public function registerRestEndpoint(): void
    {
        foreach ($this->routes as $route) {
            register_rest_route(
                $route->getNamespace() ?? $this->namespace,
                $route->getRoute(),
                [
                    'methods' => $route->getMethods(),
                    'callback' => $route->getCallback(),
                    'permission_callback' => $route->getPermissionCallback(),
                    'args' => $route->getArguments(),
                ],
            );
        }
    }
}
