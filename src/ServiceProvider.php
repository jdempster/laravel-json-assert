<?php

namespace Jdempster\JsonAssert;

use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Support\ServiceProvider AS Provider;

class ServiceProvider extends Provider
{
    public function boot(): void
    {
        if (!$this->app->runningInConsole()) {
            return;
        }

        $this->publishes([
            __DIR__ . '/config.php' => config_path('json-schema.php'),
        ], 'config');

        TestResponse::macro('assertJsonSchema', function ($schema) {
            JsonAssert::assertJsonMatchesSchema($this->content(), $schema);
            return $this;
        });

        TestResponse::macro('assertJsonValueEquals', function ($expected, $expression) {
            JsonAssert::assertJsonValueEquals($expected, $expression, $this->content());
            return $this;
        });
    }

    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/config.php', 'json-schema');
    }
}
