<?php

declare(strict_types=1);

namespace Kaiseki\WordPress\RestApi;

use Kaiseki\WordPress\Hook\HookProviderInterface;

use function add_action;
use function register_rest_route;

final class RestRouteRegistry implements HookProviderInterface
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

    public function addHooks(): void
    {
        add_action('rest_api_init', [$this, 'registerRestEndpoint']);
    }

    public function registerRestEndpoint(): void
    {
        foreach ($this->routes as $route) {
            //            var_dump($route->getRoute());
            //            var_dump($route->getNamespace() ?? $this->namespace);
            //            var_dump([
            //                'methods' => $route->getMethods(),
            ////                'callback' => $route->getCallback(),
            ////                'permission_callback' => $route->getPermissionCallback(),
            ////                'args' => $route->getArguments(),
            //            ]);
            //            die;
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
