# kaiseki/wp-rest-api

Register WordPress REST API routes from typed, container-built route definitions.

Declare your routes in config and implement small, type-safe callback classes instead of hand-wiring
`register_rest_route()` on `rest_api_init`. `RestRouteRegistry` (a `kaiseki/wp-hook` provider) reads
the `rest_api` config, resolves each callback from the container, and registers the routes for you.

## Installation

```bash
composer require kaiseki/wp-rest-api
```

Requires PHP 8.2 or newer.

## Usage

Implement a route callback (and optionally a permission callback):

```php
use Kaiseki\WordPress\RestApi\RestRouteCallbackInterface;
use WP_REST_Request;
use WP_REST_Response;

final class GetThings implements RestRouteCallbackInterface
{
    public function __invoke(WP_REST_Request $request): WP_REST_Response
    {
        return new WP_REST_Response(['things' => []]);
    }
}
```

Register `ConfigProvider`, then declare routes under the `rest_api` config key:

```php
use Kaiseki\WordPress\RestApi\RestRoutePermissionCallbackInterface;
use WP_REST_Server;

return [
    'rest_api' => [
        'namespace' => 'my-plugin/v1',
        'route_configs' => [
            'things' => [
                'methods' => WP_REST_Server::READABLE,
                'callback' => GetThings::class,
                // a callable-string, or a RestRoutePermissionCallbackInterface class-string
                'permission_callback' => '__return_true',
                'args' => [],
                // optional per-route namespace override
            ],
        ],
    ],
];
```

On `rest_api_init`, `RestRouteRegistry` registers `my-plugin/v1/things`. Each `route_configs` entry
maps a route path to its `methods`, `callback`, `permission_callback`, optional `args`, and an
optional `namespace` override. Permission callbacks are either a callable-string (e.g.
`'__return_true'`) or a class implementing `RestRoutePermissionCallbackInterface`
(`__invoke(WP_REST_Request): WP_Error|bool`).

Routes can also be supplied as ready-made `RestRouteInterface` objects via the `rest_api.routes`
config key (container class-strings), in addition to — or instead of — `route_configs`.

## Development

```bash
composer install
composer check   # check-deps, cs-check, phpstan
```

## License

MIT — see [LICENSE](LICENSE).
