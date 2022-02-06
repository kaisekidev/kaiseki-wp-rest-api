<?php

declare(strict_types=1);

namespace Kaiseki\WordPress\RestApi;

use Kaiseki\WordPress\Config\Config;
use Psr\Container\ContainerInterface;

use function array_map;

final class RouteRegistryFactory
{
    public function __invoke(ContainerInterface $container): RouteRegistry
    {
        /** @var list<class-string<RouteHandlerInterface>> $classNames */
        $classNames = Config::get($container)->array('rest_api/route_handler');
        $handlers = Config::initClassMap($container, $classNames);
        $routes = array_map(fn(RouteHandlerInterface $handler): Route => $handler->buildRoute(), $handlers);
        return new RouteRegistry(...$routes);
    }
}
