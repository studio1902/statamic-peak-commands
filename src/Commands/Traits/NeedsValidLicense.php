<?php

namespace Studio1902\PeakCommands\Commands\Traits;

use Facades\Statamic\Licensing\LicenseManager;

use function Laravel\Prompts\alert;
use function Laravel\Prompts\info;

trait NeedsValidLicense
{
    protected static bool $licensed = false;

    public function checkLicense(): void
    {
        $addonLicense = LicenseManager::addons()->first(fn ($addonLicense) => $addonLicense->addon()->id() === 'studio1902/statamic-peak-commands');

        static::$licensed = $addonLicense && $addonLicense->valid();

        if (! static::$licensed) {
            alert('You need a valid license to use this command.');
            info('You can support Peak and buy one here: https://statamic.com/addons/studio1902/peak-commands');
            exit;
        }
    }
}
