<?php

namespace Studio1902\PeakCommands\Tests;

use Illuminate\Support\Facades\Config;
use Studio1902\PeakCommands\Registry;

class RegistryTest extends TestCase
{
    public function test_paths_contains_necessary_path_types()
    {
        Config::set('statamic-peak-commands.paths', []);

        $expected = [
            'blocks' => [],
            'presets' => [],
            'sets' => [],
        ];

        $this->assertSame($expected, Registry::getPaths());
    }
}
