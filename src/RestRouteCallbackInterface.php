<?php

declare(strict_types=1);

namespace Kaiseki\WordPress\RestApi;

use WP_REST_Request;
use WP_REST_Response;

interface RestRouteCallbackInterface
{
    public function __invoke(WP_REST_Request $request): WP_REST_Response;
}
