# Changelog

All notable changes to this project will be documented in this file, in reverse chronological order by release.

## 1.0.0 - 2026-05-31

First tagged release.

### Added

- Declarative WordPress REST API route registration: `RestRouteRegistry` (a `kaiseki/wp-hook`
  provider) registers routes from the `rest_api` config on `rest_api_init`, with `RestRouteBuilder`,
  `RestRoute` / `RestRouteInterface`, and the `RestRouteCallbackInterface` /
  `RestRoutePermissionCallbackInterface` contracts. Wired via `ConfigProvider`.

### Changed

- PHP requirement is `^8.2` (PHP 8.4 is the primary target).
- Pinned the internal dependencies to SemVer ranges: `kaiseki/config` and `kaiseki/wp-hook`
  `dev-master` → `^2.0`.
- Adopted the org baseline: `kaiseki/php-coding-standard: ^1.0` with the shared PHPStan config
  (`level: max`), PHPStan 2, PHPUnit 11, `composer-require-checker: ^4.0`; CI runs via the reusable
  workflow in `kaisekidev/.github`. Static-analysis only — no test suite yet (`run-tests: false`).

### Fixed

- PHPStan 2 (`level: max`): `RestRouteRegistry` now skips routes with an empty namespace or path
  before calling `register_rest_route()` (which expects non-empty strings), and
  `RestRouteRegistryFactory` passes a `list` to the registry via `array_values()`.
