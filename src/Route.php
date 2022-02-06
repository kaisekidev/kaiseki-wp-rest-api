<?php

declare(strict_types=1);

namespace Kaiseki\WordPress\RestApi;

use function add_action;
use function array_merge;
use function register_rest_route;

final class Route
{
    public const METHOD_GET = 'GET';
    public const METHOD_POST = 'POST';
    private string $namespace;
    private string $route;
    private string $method = self::METHOD_GET;
    /** @var callable */
    private $callback;
    /** @var array<string, mixed> */
    private array $args = [];
    /** @var callable(): bool|null */
    private $permissionCallback = null;

    private function __construct(string $namespace, string $route, callable $cb)
    {
        $this->namespace = $namespace;
        $this->route = $route;
        $this->callback = $cb;
    }

    public static function create(string $namespace, string $route, callable $cb): self
    {
        return new self($namespace, $route, $cb);
    }

    /**
     * @param array<string, mixed> $args
     */
    public function withAddedArgs(array $args): self
    {
        $this->args = array_merge($this->args, $args);
        return $this;
    }

    public function withMethod(string $method): self
    {
        $this->method = $method;
        return $this;
    }

    /**
     * @param callable(): bool $callable
     */
    public function withPermissionCallback(callable $callable): self
    {
        $this->permissionCallback = $callable;
        return $this;
    }

    public function register(): void
    {
        add_action('rest_api_init', [$this, 'registerRestRoute']);
    }

    public function registerRestRoute(): void
    {
        register_rest_route(
            $this->namespace,
            $this->route,
            [
                'methods' => $this->method,
                'callback' => $this->callback,
                'args' => $this->args,
                'permission_callback' => $this->permissionCallback ?? '__return_true',
            ]
        );
    }
}
