<?php

namespace Brocc\LaravelOpenApi\Http\Controllers;

use Brocc\LaravelOpenApi\Exceptions\OpenApiException;
use Brocc\LaravelOpenApi\OpenApi;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\File;

class OpenApiController extends Controller
{
    /**
     * @param Request $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function ui(Request $request)
    {
        $documentation = $this->documentation($request);
        $config = $this->config($request);

        $specUrl = route("openapi.{$documentation}.openapi");

        return view('openapi::index', [
            'title'   => $config['title'],
            'specUrl' => $specUrl,
        ]);
    }

    /**
     * @param Request     $request
     * @param string|null $file
     *
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     * @throws OpenApiException
     */
    public function openapi(Request $request, string $file = null)
    {
        if (config('openapi.generate_always')) {
            $this->generateDocs($request);
        }

        $documentation = $this->documentation($request);

        $path = openapi_storage_path($documentation);

        if (! File::exists($path)) {
            abort(404);
        }

        $contents = file_get_contents($path);

        $yaml = strtolower(File::extension($path)) !== 'json';

        $headers = $yaml
            ? ['Content-Type' => 'application/yaml']
            : ['Content-Type' => 'application/json'];

        return response($contents, 200, $headers);
    }

    /**
     * Generate docs based on requested documentation.
     *
     * @param Request $request
     *
     * @throws OpenApiException
     */
    protected function generateDocs(Request $request): void
    {
        $config = $this->config($request);
        $documentation = $this->documentation($request);

        $openapi = new OpenApi($config['spec']);

        if (! $openapi->isValid()) {
            $errors = $openapi->getErrors();
            $message = empty($errors) ? 'Invalid OpenAPI Specification' : $errors[0];

            throw new OpenApiException($message);
        }

        $path = openapi_storage_path($documentation);

        $openapi->save($path);
    }

    /**
     * Get the requested documentation config.
     *
     * @param Request $request
     *
     * @return array
     */
    protected function config(Request $request): array
    {
        $documentation = $this->documentation($request);

        return config("openapi.documentations.{$documentation}");
    }

    /**
     * Get the documentation name from request.
     *
     * @param Request $request
     *
     * @return string
     */
    protected function documentation(Request $request): string
    {
        return $request->route()->getAction('documentation');
    }
}