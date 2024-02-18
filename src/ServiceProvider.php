<?php

namespace Studio1902\PeakCommands;

use Facades\Statamic\Licensing\LicenseManager;
use Statamic\Providers\AddonServiceProvider;

class ServiceProvider extends AddonServiceProvider
{
    protected static $shouldBoot = false;

    protected $commands = [
        Commands\AddBlock::class,
        Commands\AddCollection::class,
        Commands\AddPartial::class,
        Commands\AddSet::class,
        Commands\InstallBlock::class,
        Commands\InstallPreset::class,
    ];

    protected $updateScripts = [
        \Studio1902\PeakCommands\Updates\UpdateRSSFeed::class,
        \Studio1902\PeakCommands\Updates\UpdateJSONldDateFormatting::class,
    ];

    public function __construct($app)
    {
        parent::__construct($app);

        $this->app->booted(function () {
            $addonLicense = LicenseManager::addons()->first(function ($addonLicense) {
                return $addonLicense->addon()->id() === 'studio1902/statamic-peak-commands';
            });

            static::$shouldBoot = $addonLicense && $addonLicense->valid();
        });
    }

    public function boot()
    {
        $this->app->booted(function () {
            if (static::$shouldBoot) {
                parent::boot();
            }
        });
    }

    public function bootAddon()
    {
        $this->registerPublishableStubs();
    }

    protected function registerPublishableStubs()
    {
        $this->publishes([
            __DIR__ . '/../resources/stubs' => resource_path('stubs/vendor/statamic-peak-commands'),
        ], 'statamic-peak-commands-stubs');
    }
}
