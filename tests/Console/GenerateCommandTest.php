<?php

namespace Brocc\LaravelOpenApi\Tests\Console;

use Brocc\LaravelOpenApi\Tests\TestCase;

class GenerateCommandTest extends TestCase
{
    public function testDocsAreGenerated()
    {
        $this->artisan('openapi:generate');

        $path = openapi_storage_path('default');

        $this->assertFileExists($path);
    }
}