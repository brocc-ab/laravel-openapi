# Write OpenAPI Specifications for Laravel Applications

[![Latest Version](https://img.shields.io/github/release/brocc-ab/laravel-openapi.svg?style=flat-square)](https://github.com/brocc-ab/laravel-openapi/releases)
[![StyleCI](https://github.styleci.io/repos/341976946/shield?branch=master)](https://github.styleci.io/repos/341976946?branch=master)
[![License](https://img.shields.io/packagist/l/brocc/laravel-openapi)](https://packagist.org/packages/brocc/laravel-openapi)

This packages simplifies writing and structuring of OpenAPI Specifications in YAML or JSON.

## Introduction

OpenAPI Specifications generated from annotations or auto-generated from code is great, especially when working with smaller API's. 
But when an API grows and gets more complex, using annotations can easily become a mess, ending up in more annotations than actual code.

This package helps writing and structuring [OpenAPI Specifications](https://swagger.io/specification/) written in YAML or JSON. 
Specifications with referenced parts are merged into a single specification file. 
This package also comes with [ReDoc](https://github.com/Redocly/redoc) as an UI for your documentation.

## Installation

To get started, install Laravel OpenAPI via composer:

```
composer require brocc/laravel-openapi
```
 
You can optionally publish the config file and views with:
 
```
php artisan vendor:publish --tag=openapi-config
php artisan vendor:publish --tag=openapi-views
```

By default this package will check for an OpenAPI Specification file located in `base_path('/api-docs/openapi.yaml')`, this can easily be changes in the config file.

To generate a specification run the following command:

```
php artisan openapi:generate
```

## Multiple Specifications

You might have multiple specifications, eg. a public API and an internal API, or even multiple API versions that you wish to separate into different specifications.

### Example of multi-version config.

You can easily generate multiple specifications by adding additional documentations in `config/openapi.php`:

```php
'documentations'  => [
    'default' => [
        'title' => 'Docs - latest',
        
        // Absolute path to the main OpenAPI specification.
        'spec'  => base_path('api-docs/v2/openapi.yaml'),

        'routes' => [
            // Route for accessing documentation UI.
            'ui'         => '/docs',

            // Route to OpenAPI specification file, can be json or yaml.
            'openapi'    => '/openapi/v2/openapi.json',

            // Middleware to be applied on the ui and docs, eg. api, auth, trusted_proxies etc.
            'middleware' => [
                'ui'      => [],
                'openapi' => [],
            ],
        ],
    ],
    'v1' => [
        'title' => 'Docs - v1',

        'spec'  => base_path('api-docs/v1/openapi.yaml'),

        'routes' => [
            'ui'         => '/docs/v1',

            'openapi'    => '/openapi/v1/openapi.json',

            'middleware' => [
                'ui'      => [],
                'openapi' => [],
            ],
        ],
    ],
],
```

This will generate two documentations accessible at `/docs` and `/docs/v1` in your browser,
with the specifications accessible at `/openapi/v2/openapi.json` and `/openapi/v1/openapi.json`.

You can easily chose to publish only one specification by specifying the documentation in the `openapi:generate` command:

```
php artisan openapi:generate v1
```

## Development

During development you might want to see the updated docs every time a specification has changed.
This can be done by setting `generate_always` to be `true`. 
It is recommended to keep it set to `false` in production.

## Contributing

Any contributions are welcome!

We accept contributions via Pull Requests on Github.