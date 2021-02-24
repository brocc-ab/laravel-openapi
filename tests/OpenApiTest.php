<?php

namespace Brocc\LaravelOpenApi\Tests;

use Brocc\LaravelOpenApi\Exceptions\OpenApiException;
use Brocc\LaravelOpenApi\OpenApi;

class OpenApiTest extends TestCase
{
    public function testItThrowsExceptionIfSpecDoesNotExist()
    {
        $this->expectException(OpenApiException::class);

        new OpenApi('/file/does/not/exist.yaml');
    }

    public function testSavingFromYaml()
    {
        $openapi = new OpenApi(__DIR__ . '/openapi/openapi.yaml');

        $this->assertTrue($openapi->isValid());
        $this->assertEmpty($openapi->getErrors());

        $path = $this->storage('openapi.yaml');
        $this->assertTrue($openapi->save($path));
        $this->assertFileExists($path);
    }

    public function testSavingFromJson()
    {
        $openapi = new OpenApi(__DIR__ . '/openapi/openapi.json');

        $this->assertTrue($openapi->isValid());
        $this->assertEmpty($openapi->getErrors());

        $path = $this->storage('openapi.json');

        $this->assertTrue($openapi->save($path));
        $this->assertFileExists($path);
    }

    public function testItReturnsErrorsWhenGivenInvalidSpec()
    {
        $openapi = new OpenApi(__DIR__ . '/openapi/invalid.yaml');

        $this->assertFalse($openapi->isValid());
        $this->assertNotEmpty($openapi->getErrors());
    }

    public function testItDoesNotSaveDocWhenInvalidSpec()
    {
        $openapi = new OpenApi(__DIR__ . '/openapi/invalid.yaml');

        $path = $this->storage('invalid.json');

        $this->assertFalse($openapi->save($path));
        $this->assertFileDoesNotExist($path);
    }
}