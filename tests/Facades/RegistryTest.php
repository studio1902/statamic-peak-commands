<?php

namespace Studio1902\PeakCommands\Tests\Facades;

use Studio1902\PeakCommands\Facades\Registry;
use Studio1902\PeakCommands\RegistryManager;
use Studio1902\PeakCommands\Tests\TestCase;

class RegistryTest extends TestCase
{
    public function test_it_returns_namespaces()
    {
        $this->assertIsArray(Registry::getNamespaces());
    }

    public function test_it_returns_paths()
    {
        $this->assertIsArray(Registry::getPaths());
    }

    public function test_paths_contain_necessary_path_types()
    {
        $paths = Registry::getPaths();

        $this->assertArrayHasKey('blocks', $paths);
        $this->assertArrayHasKey('presets', $paths);
        $this->assertArrayHasKey('sets', $paths);
    }

    public function test_it_can_add_a_presets_path()
    {
        $path = 'presets/path';

        $this->assertFalse(in_array($path, collect(Registry::getPaths())->get(RegistryManager::PRESETS)));

        Registry::addPresetsPath($path);

        $this->assertTrue(in_array($path, collect(Registry::getPaths())->get(RegistryManager::PRESETS)));
    }

    public function test_it_can_add_a_sets_path()
    {
        $path = 'sets/path';

        $this->assertFalse(in_array($path, collect(Registry::getPaths())->get(RegistryManager::SETS)));

        Registry::addSetsPath($path);

        $this->assertTrue(in_array($path, collect(Registry::getPaths())->get(RegistryManager::SETS)));
    }

    public function test_it_can_add_a_blocks_path()
    {
        $path = 'blocks/path';

        $this->assertFalse(in_array($path, collect(Registry::getPaths())->get(RegistryManager::BLOCKS)));

        Registry::addBlocksPath($path);

        $this->assertTrue(in_array($path, collect(Registry::getPaths())->get(RegistryManager::BLOCKS)));
    }

    public function test_it_can_remove_a_presets_path()
    {
        $path = 'presets/path';
        config(['statamic-peak-commands.paths.'.RegistryManager::PRESETS => [$path]]);

        $this->assertTrue(in_array($path, collect(Registry::getPaths())->get(RegistryManager::PRESETS)));

        Registry::removePresetsPath($path);

        $this->assertFalse(in_array($path, collect(Registry::getPaths())->get(RegistryManager::PRESETS)));
    }

    public function test_it_can_remove_a_sets_path()
    {
        $path = 'sets/path';
        config(['statamic-peak-commands.paths.'.RegistryManager::SETS => [$path]]);

        $this->assertTrue(in_array($path, collect(Registry::getPaths())->get(RegistryManager::SETS)));

        Registry::removeSetsPath($path);

        $this->assertFalse(in_array($path, collect(Registry::getPaths())->get(RegistryManager::SETS)));
    }

    public function test_it_can_remove_a_blocks_path()
    {
        $path = 'blocks/path';
        config(['statamic-peak-commands.paths.'.RegistryManager::BLOCKS => [$path]]);

        $this->assertTrue(in_array($path, collect(Registry::getPaths())->get(RegistryManager::BLOCKS)));

        Registry::removeBlocksPath($path);

        $this->assertFalse(in_array($path, collect(Registry::getPaths())->get(RegistryManager::BLOCKS)));
    }

    public function test_it_can_append_a_namespace()
    {
        $namespace = 'custom/namespace';
        config(['statamic-peak-commands.namespaces' => ['default/namespace']]);

        $this->assertFalse(in_array($namespace, Registry::getNamespaces()));

        Registry::appendNamespace($namespace);

        $this->assertSame($namespace, collect(Registry::getNamespaces())->last());
    }

    public function test_it_can_prepend_a_namespace()
    {
        $namespace = 'custom/namespace';
        config(['statamic-peak-commands.namespaces' => ['default/namespace']]);

        $this->assertFalse(in_array($namespace, Registry::getNamespaces()));

        Registry::prependNamespace($namespace);

        $this->assertSame($namespace, collect(Registry::getNamespaces())->first());
    }

    public function test_it_can_remove_a_namespace()
    {
        $namespace = 'custom/namespace';
        config(['statamic-peak-commands.namespaces' => [$namespace]]);

        $this->assertTrue(in_array($namespace, Registry::getNamespaces()));

        Registry::removeNamespace($namespace);

        $this->assertFalse(in_array($namespace, Registry::getNamespaces()));
    }
}
