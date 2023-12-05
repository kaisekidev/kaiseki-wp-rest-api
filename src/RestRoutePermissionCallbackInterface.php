<?php

declare(strict_types=1);

namespace Kaiseki\WordPress\RestApi;

use WP_Error;
use WP_REST_Request;

interface RestRoutePermissionCallbackInterface
{
    public function __invoke(WP_REST_Request $request): WP_Error|bool;
}
