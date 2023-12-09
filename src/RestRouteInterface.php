<?php

declare(strict_types=1);

namespace Kaiseki\WordPress\RestApi;

interface RestRouteInterface
{
    /**
     * Get the REST API endpoint route.
     *
     * @return string
     */
    public function getRoute(): string;

    /**
     * Get the HTTP methods that the REST API endpoint responds to.
     *
     * @return list<string>|string
     */
    public function getMethods(): string|array;

    /**
     * Get the callback used by the REST API endpoint.
     *
     * @return RestRouteCallbackInterface
     */
    public function getCallback(): RestRouteCallbackInterface;

    /**
     * Get the callback used to validate a request to the REST API endpoint.
     *
     * @return RestRoutePermissionCallbackInterface
     */
    public function getPermissionCallback(): RestRoutePermissionCallbackInterface;

    /**
     * Get the expected arguments for the REST API endpoint.
     *
     * @return array<string, mixed>
     */
    public function getArguments(): array;

    /**
     * Get the namespace for the REST API endpoint.
     *
     * @return ?string
     */
    public function getNamespace(): ?string;
}
