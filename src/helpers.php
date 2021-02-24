<?php

if (! function_exists('openapi_storage_path')) {
    /**
     * Returns the stored path of the generated file for a given documentation.
     *
     * @param string $documentation
     *
     * @return string
     */
    function openapi_storage_path(string $documentation): string
    {
        $storage = config('openapi.storage_path');
        $file = config("openapi.documentations.{$documentation}.routes.openapi");

        return rtrim($storage, '/') . '/' . ltrim($file, '/');
    }
}
