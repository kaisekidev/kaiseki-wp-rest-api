<?php

declare(strict_types=1);

namespace Kaiseki\WordPress\RestApi;

class RestRoute implements RestRouteInterface
{
    /**
     * @param string                                    $route
     * @param list<string>|string                       $methods
     * @param RestRouteCallbackInterface                $callback
     * @param RestRoutePermissionCallbackInterface      $permissionCallback
     * @param array<string, mixed>                      $arguments
     * @param ?string                                   $namespace
     */
    public function __construct(
        private readonly string $route,
        private readonly string|array $methods,
        private readonly RestRouteCallbackInterface $callback,
        private readonly RestRoutePermissionCallbackInterface $permissionCallback,
        private readonly array $arguments = [],
        private ?string $namespace = null,
    ) {
    }

    /**
     * Get the REST API endpoint route.
     *
     * @return string
     */
    public function getRoute(): string
    {
        return $this->route;
    }

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
        return $this->methods;
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
        return $this->arguments;
    }

    /**
     * Get the namespace for the REST API endpoint.
     *
     * @return ?string
     */
    public function getNamespace(): ?string
    {
        return $this->namespace;
    }

    /**
     * Get a copy of the REST API endpoint with a different namespace.
     *
     * @param string $namespace
     *
     * @return RestRouteInterface
     */
    public function withNamespace(string $namespace): RestRouteInterface
    {
        $clone = clone $this;
        $clone->namespace = $namespace;
        return $clone;
    }
}
