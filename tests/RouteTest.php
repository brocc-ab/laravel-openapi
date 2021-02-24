<?php

namespace Brocc\LaravelOpenApi\Tests;

class RouteTest extends TestCase
{
    public function testGeneratedJsonDocIsAccessible()
    {
        config(['openapi.documentations.default.routes.openapi' => 'openapi/openapi.json']);

        $url = $this->specUrl();

        $this->get($url)
            ->assertHeader('Content-Type', 'application/json')
            ->assertOk();
    }

    public function testGeneratedYamlDocIsAccessible()
    {
        config(['openapi.documentations.default.routes.openapi' => 'openapi/openapi.yaml']);

        $url = $this->specUrl();

        $this->get($url)
            ->assertHeader('Content-Type', 'application/yaml')
            ->assertOk();
    }

    public function testGeneratedDocIsAccessibleWithoutFilename()
    {
        $this->get($this->specUrl())
            ->assertOk();
    }

    public function testDocUiIsAccessible()
    {
        $url = route('openapi.default.ui');

        $this->get($url)
            ->assertViewHasAll([
                'title'   => config('openapi.documentations.default.title'),
                'specUrl' => $this->specUrl(),
            ])
            ->assertOk();
    }

    public function testItReturnsServerErrorWhenInvalidSpec()
    {
        config(['openapi.documentations.default.spec' => __DIR__.'/openapi/invalid.json']);

        $this->get($this->specUrl())
            ->assertStatus(500);
    }

    protected function specUrl(): string
    {
        return route('openapi.default.openapi');
    }
}
