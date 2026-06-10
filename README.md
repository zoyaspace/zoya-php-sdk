# Zoya PHP SDK

Framework-agnostic PHP SDK for the Zoya API.

## Goals

- work in plain PHP and Laravel
- keep the core transport-neutral and framework-neutral
- allow endpoint resources to be added incrementally
- make API simulation and contract testing easy before the API surface is finalized

## Current Scope

This package currently provides the foundation layer and the first public API resource helpers:

- immutable SDK configuration
- API key authentication for the public API
- fixed environment mapping for `prod` and `dev`
- versioned public API prefix configuration
- PSR-18 transport support
- request factory based HTTP executor
- normalized response wrapper
- API exceptions
- fake transport for tests and API simulation
- OpenAPI contract scaffold for future code generation and mock servers
- convenience methods for public property and lead endpoints

## OpenAPI

The package includes a starter OpenAPI contract in `openapi/openapi.yaml`.

The intended workflow is:

- define or refine endpoints in OpenAPI first
- use the spec as the contract for backend, SDK, and external integrations
- use the spec to run a mock server during SDK development before the API is fully implemented
- keep ergonomic SDK resource classes hand-written on top of the contract instead of exposing generator output directly

## Local Development

```bash
composer install
composer test
```

## Installation

After the package is submitted to Packagist, the standard installation command is:

```bash
composer require zoyaspace/zoya-php-sdk
```

Until Packagist has indexed the package, you can use the GitHub VCS repository directly:

Via Composer VCS repository:

```json
{
    "repositories": [
        {
            "type": "vcs",
            "url": "https://github.com/zoyaspace/zoya-php-sdk.git"
        }
    ],
    "require": {
        "zoyaspace/zoya-php-sdk": "dev-main"
    }
}
```

Then install dependencies:

```bash
composer install
```

After the first stable tag is published and Packagist has indexed the package, prefer a version constraint such as `^0.1` instead of `dev-main`.

## Quick Start

```php
use Zoya\Sdk\Client\ZoyaClientFactory;
use Zoya\Sdk\Client\ZoyaEnvironment;

$apiToken = 'zya_xxxxx.yyyyy';

$client = ZoyaClientFactory::make(
    apiToken: $apiToken,
    environment: ZoyaEnvironment::Production,
    apiVersion: 'v1',
);

$investments = $client->listPropertyInvestments([
    'page' => ['number' => 1, 'size' => 20],
    'filter' => ['status' => ['active']],
    'sort' => '-created_at',
]);

$investment = $client->getPropertyInvestment('piv_xxxxxxxxxxxxxxxxxxxxxxxx');

$lead = $client->createLead([
    'contact_name' => 'Jan Kowalski',
    'contact_email' => 'jan.kowalski@example.com',
    'organization_id' => 'org_xxxxxxxxxxxxxxxxxxxxxxxx',
]);

$leadSources = $client->listLeadSourcesPage();
$firstLeadSource = $leadSources->items[0] ?? null;
```

If the integrator needs lower-level control, the SDK still exposes the manual `ZoyaClient` + `Psr18Transport` construction path.

## API Token

The SDK user should paste the API token in application configuration, not directly inside reusable library code.

For plain PHP:

```php
$apiToken = getenv('ZOYA_API_TOKEN');
```

For Laravel:

```php
$apiToken = config('services.zoya.api_token');
```

The token is then passed into the SDK through:

```php
ZoyaClientFactory::make(apiToken: $apiToken, ...)
```

The SDK sends it in the `X-Zoya-Api-Key` request header, which matches the current public API contract.

## Environments

The SDK supports only the official public API environments and always builds requests under the `/public/{version}` routing prefix:

- `ZoyaEnvironment::Production` -> `https://api.zoyaspace.com`
- `ZoyaEnvironment::Development` -> `https://api-dev.zoyaspace.com`

The public API version is configured separately:

- `apiVersion: 'v1'` -> requests are sent to `/api/public/v1/...`
- `apiVersion: 'v2'` -> requests would be sent to `/api/public/v2/...`

Example:

```php
ZoyaClientFactory::make(
    apiToken: $apiToken,
    environment: ZoyaEnvironment::Development,
    apiVersion: 'v1',
)
```

That means `listLeadSources()` targets:

```text
https://api-dev.zoyaspace.com/api/public/v1/lead-sources
```

For normal usage, the SDK user should choose one of the supported environments and an API version instead of passing custom server URLs. `baseUrlOverride` exists only for local SDK development, mock servers, and contract testing.

## Examples

- [Plain PHP](examples/plain-php.php)
- [Laravel](examples/laravel.php)

## Public API Helpers

The client currently exposes convenience methods for:

- `listPropertyInvestments(array $query = [])`
- `getPropertyInvestment(string $publicId)`
- `listProducts(array $query = [])`
- `getProduct(string $publicId)`
- `listPropertyBuildings(array $query = [])`
- `getPropertyBuilding(string $publicId)`
- `listPropertyUnits(array $query = [])`
- `getPropertyUnit(string $publicId)`
- `listLeadSources(array $query = [])`
- `getLeadSource(string $publicId)`
- `listSalesChannels(array $query = [])`
- `getSalesChannel(string $publicId)`
- `createLead(array $payload)`

For better DX, the client also exposes typed helpers for selected collection and detail responses:

- `listPropertyInvestmentsPage(array $query = [])`
- `getPropertyInvestmentData(string $publicId)`
- `listProductsPage(array $query = [])`
- `getProductData(string $publicId)`
- `listPropertyBuildingsPage(array $query = [])`
- `getPropertyBuildingData(string $publicId)`
- `listPropertyUnitsPage(array $query = [])`
- `getPropertyUnitData(string $publicId)`
- `listLeadSourcesPage(array $query = [])`
- `getLeadSourceData(string $publicId)`
- `listSalesChannelsPage(array $query = [])`
- `getSalesChannelData(string $publicId)`
- `createLeadData(array $payload)`

The `array $query` arguments accept nested PHP arrays and are serialized with PHP-compatible bracket notation, for example:

```php
[
    'page' => ['number' => 1, 'size' => 20],
    'filter' => ['status' => ['active']],
    'sort' => '-created_at',
]
```
