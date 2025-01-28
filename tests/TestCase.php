<?php

namespace Studio1902\PeakCommands\Tests;

use Studio1902\PeakCommands\ServiceProvider;
use Statamic\Testing\AddonTestCase;

abstract class TestCase extends AddonTestCase
{
    protected string $addonServiceProvider = ServiceProvider::class;
}
