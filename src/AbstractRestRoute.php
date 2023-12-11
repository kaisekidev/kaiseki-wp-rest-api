<?php

declare(strict_types=1);

namespace Kaiseki\WordPress\RestApi;

use WP_REST_Server;

abstract class AbstractRestRoute
{
    /**
     * Get the HTTP methods that the REST API endpoint responds to.
     *
     * @return list<string>|string
     */
    public function getMethods(): string|array
    {
        return WP_REST_Server::READABLE;
    }

    /**
     * Get the expected arguments for the REST API endpoint.
     *
     * @return array<string, mixed>
     */
    public function getArguments(): array
    {
        return [];
    }

    /**
     * Get the namespace for the REST API endpoint.
     *
     * @return ?string
     */
    public function getNamespace(): ?string
    {
        return null;
    }
}
