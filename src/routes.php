<?php

use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;

Route::group(['namespace' => 'Brocc\LaravelOpenApi\Http\Controllers'], function (Router $router) {
    $configs = config('openapi.documentations', []);

    foreach ($configs as $documentation => $config) {
        if (! isset($config['routes'])) {
            continue;
        }

        $actions = [
            'documentation' => $documentation,
        ];

        $router->group($actions, function (Router $router) use ($documentation, $config) {
            $router->get($config['routes']['ui'], 'OpenApiController@ui')
                ->middleware($config['routes']['middleware']['ui'] ?? [])
                ->name("openapi.{$documentation}.ui");

            $router->get($config['routes']['openapi'], 'OpenApiController@openapi')
                ->middleware($config['routes']['middleware']['openapi'] ?? [])
                ->name("openapi.{$documentation}.openapi");
        });
    }
});
