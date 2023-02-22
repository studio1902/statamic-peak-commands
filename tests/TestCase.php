<?php

namespace Studio1902\PeakCommands\Tests;

use Orchestra\Testbench\TestCase as OrchestraTestCase;
use Studio1902\PeakCommands\ServiceProvider as PeakCommandsServiceProvider;

class TestCase extends OrchestraTestCase
{

    protected function getPackageProviders($app): array
    {
        return [
            PeakCommandsServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app): void
    {
        config()->set('database.default', 'testing');
    }
}
