<?php

namespace Brocc\LaravelOpenApi\Tests;

use Brocc\LaravelOpenApi\OpenApiServiceProvider;
use Illuminate\Support\Facades\File;

abstract class TestCase extends \Orchestra\Testbench\TestCase
{
    public function tearDown(): void
    {
        File::deleteDirectory($this->storage());

        parent::tearDown();
    }

    protected function getEnvironmentSetUp($app): void
    {
        $app['config']->set('openapi.storage_path', $this->storage());
        $app['config']->set(
            'openapi.documentations.default.spec',
            __DIR__.'/openapi/openapi.yaml'
        );
        $app['config']->set('openapi.generate_always', true);
    }

    protected function getPackageProviders($app): array
    {
        return [OpenApiServiceProvider::class];
    }

    protected function storage(string $filename = null): string
    {
        $path = __DIR__.'/storage';

        if ($filename) {
            $path .= '/'.ltrim($filename, '/');
        }

        return $path;
    }
}
