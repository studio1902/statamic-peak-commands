<?php

namespace Studio1902\PeakCommands\Commands;

use Facades\Statamic\Licensing\LicenseManager;

trait NeedsValidLicense {

    protected static $licensed = false;

    public function __construct()
    {
        parent::__construct();

        $addonLicense = LicenseManager::addons()->first(function ($addonLicense) {
            return $addonLicense->addon()->id() === 'studio1902/statamic-peak-commands';
        });

        static::$licensed = $addonLicense && $addonLicense->valid();
    }

    public function checkLicense() {
        if (! static::$licensed) {
            $this->info("You need a valid license to use this command.");
            die;
        }
    }
}
