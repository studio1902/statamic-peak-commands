<?php

namespace Studio1902\PeakCommands\Facades;

use Illuminate\Support\Facades\Facade;
use Studio1902\PeakCommands\Operations\Operation;
use Studio1902\PeakCommands\RegistryManager;

/**
 * @method static array getNamespaces()
 * @method static array getPaths()
 * @method static void addPresetsPath(string $path)
 * @method static void addBlocksPath(string $path)
 * @method static void addSetsPath(string $path)
 * @method static void removePresetsPath(string $path)
 * @method static void removeBlocksPath(string $path)
 * @method static void removeSetsPath(string $path)
 * @method static void appendNamespace(string $namespace)
 * @method static void prependNamespace(string $namespace)
 * @method static void removeNamespace(string $namespace)
 * @method static Operation resolveOperation(string $class, array $config)
 *
 * @see \Studio1902\PeakCommands\RegistryManager
 */
class Registry extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return RegistryManager::class;
    }
}
