<?php

namespace Studio1902\PeakCommands;

use Statamic\Providers\AddonServiceProvider;

class ServiceProvider extends AddonServiceProvider
{
    protected $commands = [
        Commands\MakeBlock::class,
        Commands\MakeCollection::class,
        Commands\MakeGlobal::class,
        Commands\MakeNav::class,
        Commands\MakePartial::class,
        Commands\MakeSet::class,
        Commands\MakeTaxonomy::class,
        Commands\ClearSite::class,
        Commands\InstallBlock::class,
        Commands\InstallPreset::class,
        Commands\InstallSet::class,
    ];

    protected $updateScripts = [
        \Studio1902\PeakCommands\Updates\UpdateRSSFeed::class,
        \Studio1902\PeakCommands\Updates\UpdateJSONldDateFormatting::class,
    ];

    public function bootAddon(): void
    {
        $this->registerPublishableStubs();
    }

    protected function registerPublishableStubs(): void
    {
        $this->publishes([
            __DIR__.'/../resources/stubs' => resource_path('stubs/vendor/statamic-peak-commands'),
        ], 'statamic-peak-commands-stubs');
    }
}
