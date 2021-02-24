<?php

namespace Brocc\LaravelOpenApi\Console;

use Brocc\LaravelOpenApi\OpenApi;
use Illuminate\Console\Command;

class GenerateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'openapi:generate {documentation?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate OpenAPI definition';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $configs = config('openapi.documentations');

        if ($documentation = $this->argument('documentation')) {
            $configs = collect($configs)->filter(function ($value, $key) use ($documentation) {
                return $key === $documentation;
            })->toArray();
        }

        foreach ($configs as $documentation => $config) {
            $openapi = new OpenApi($config['spec']);

            if (! $openapi->isValid()) {
                foreach ($openapi->getErrors() as $error) {
                    $this->error($error);
                }

                return 1;
            }

            $openapi->save(openapi_storage_path($documentation));

            $this->info("Generated docs for \"{$documentation}\"");
        }

        return 0;
    }
}