<?php

return [
    'documentations'  => [
        'default' => [
            'title' => 'Laravel OpenAPI',

            // Absolute path to the main OpenAPI specification.
            'spec'  => base_path('api-docs/openapi.yaml'),

            'routes' => [
                // Route for accessing documentation UI.
                'ui'         => '/docs',

                // Route to OpenAPI specification file, can be json or yaml.
                'openapi'    => '/openapi/openapi.json',

                // Middleware to be applied on the ui and docs, eg. api, auth, trusted_proxies etc.
                'middleware' => [
                    'ui'      => [],
                    'openapi' => [],
                ],
            ],
        ],
    ],

    // Absolute path where the generated docs will be stored.
    'storage_path'    => storage_path('api-docs'),

    // Set to "true" to generate docs on each request.
    'generate_always' => env('OPENAPI_GENERATE_ALWAYS', false),
];
