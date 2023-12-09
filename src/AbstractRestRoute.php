<?php

declare(strict_types=1);

namespace Kaiseki\WordPress\RestApi;

use WP_REST_Server;

abstract class AbstractRestRoute
{
    protected RestRouteCallbackInterface $callback;
    protected RestRoutePermissionCallbackInterface $permissionCallback;

    /**
     * Get the callback used by the REST API endpoint.
     *
     * @return RestRouteCallbackInterface
     */
    public function getCallback(): RestRouteCallbackInterface
    {
        return $this->callback;
    }

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
     * Get the callback used to validate a request to the REST API endpoint.
     *
     * @return RestRoutePermissionCallbackInterface
     */
    public function getPermissionCallback(): RestRoutePermissionCallbackInterface
    {
        return $this->permissionCallback;
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
