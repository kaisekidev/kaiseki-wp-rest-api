<?php

declare(strict_types=1);

namespace Kaiseki\WordPress\RestApi;

use Kaiseki\WordPress\Hook\HookCallbackProviderInterface;

interface RouteHandlerInterface extends HookCallbackProviderInterface
{
    public function buildRoute(): Route;
}
