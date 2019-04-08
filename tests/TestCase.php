<?php

namespace Tests;

use Jdempster\JsonAssert\ServiceProvider;
use Orchestra\Testbench\TestCase as OrchestraTestCase;

abstract class TestCase extends OrchestraTestCase
{
    protected function getPackageProviders($app): array
    {
        return [ServiceProvider::class];
    }

    public function getEnvironmentSetUp($app): void
    {
        $app['config']->set('json-schema.schema_base_path', __DIR__ . '/schemas');
    }
}
