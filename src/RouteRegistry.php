<?php

declare(strict_types=1);

namespace Kaiseki\WordPress\RestApi;

use Kaiseki\WordPress\Hook\HookCallbackProviderInterface;

final class RouteRegistry implements HookCallbackProviderInterface
{
    /** @var list<Route> */
    private array $routes;

    public function __construct(Route ...$routes)
    {
        $this->routes = $routes;
    }

    public function registerCallbacks(): void
    {
        foreach ($this->routes as $route) {
            $route->register();
        }
    }
}
