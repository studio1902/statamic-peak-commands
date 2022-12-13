<?php

namespace Studio1902\Peak;

use Statamic\Providers\AddonServiceProvider;

class ServiceProvider extends AddonServiceProvider
{
    protected $commands = [
        Commands\AddBlock::class,
        Commands\AddCollection::class,
        Commands\AddPartial::class,
        Commands\AddSet::class,
        Commands\ClearSite::class,
        Commands\InstallBlock::class,
        Commands\InstallPreset::class,
    ];

    public function bootAddon()
    {
        //
    }
}
