<?php

namespace Brocc\LaravelOpenApi;

use Brocc\LaravelOpenApi\Exceptions\OpenApiException;
use cebe\openapi\Reader;
use cebe\openapi\Writer;
use Illuminate\Support\Facades\File;

class OpenApi
{
    /**
     * @var \cebe\openapi\spec\OpenApi
     */
    private $openApi;

    /**
     * @var bool
     */
    private $isValid;

    /**
     * @var array|null
     */
    private $errors;

    /**
     * @param string $spec
     *
     * @throws OpenApiException
     */
    public function __construct(string $spec)
    {
        $this->readFromFile($spec);
    }

    /**
     * @param string $filename
     *
     * @return bool
     * @throws \cebe\openapi\exceptions\IOException
     */
    public function save(string $filename): bool
    {
        if (! $this->isValid || ! empty($this->errors)) {
            return false;
        }

        File::ensureDirectoryExists(File::dirname($filename));

        $this->isJson($filename)
            ? Writer::writeToJsonFile($this->openApi, $filename)
            : Writer::writeToYamlFile($this->openApi, $filename);

        return true;
    }

    /**
     * @return bool
     */
    public function isValid(): bool
    {
        return $this->isValid;
    }

    /**
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors ?? [];
    }

    /**
     * @param string $spec
     *
     * @throws OpenApiException
     */
    private function readFromFile(string $spec): void
    {
        if (! File::exists($spec)) {
            throw new OpenApiException("{$spec} does not exist");
        }

        $this->openApi = $this->isJson($spec)
            ? Reader::readFromJsonFile($spec)
            : Reader::readFromYamlFile($spec);

        $this->isValid = $this->openApi->validate();
        $this->errors = $this->openApi->getErrors();
    }

    /**
     * @param string $filename
     *
     * @return string
     */
    protected function isJson(string $filename): string
    {
        $extension = File::extension($filename);

        return strtolower($extension) === 'json';
    }
}
