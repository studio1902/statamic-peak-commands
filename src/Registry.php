<?php

namespace Studio1902\PeakCommands;

use Exception;

class Registry
{
    //TODO[mr]: refactor to enums (28.01.2025 mr)
    const BLOCKS = 'blocks';
    const PRESETS = 'presets';
    const SETS = 'sets';

    protected static ?Registry $instance = null;

    protected array $namespaces = ['\Studio1902\PeakCommands\Operations'];
    protected array $paths = [self::BLOCKS => [], self::PRESETS => [], self::SETS => [],];


    protected function __construct()
    {
        $this->paths = array_merge($this->paths, config('statamic-peak-commands.paths', []));
    }

    public static function instance(): Registry
    {
        if (!self::$instance) {
            self::$instance = new static();
        }

        return self::$instance;
    }

    public static function getNamespaces(): array
    {
        return self::instance()->namespaces;
    }

    public static function getPaths(): array
    {
        return self::instance()->paths;
    }

    public static function addPath(string $path, string $type): void
    {
        if (!self::isValidPathType($type)) {
            throw new Exception("Unknown path type '{$type}'");
        }

        self::instance()->paths[$type][] = $path;
    }

    public static function addPresetsPath(string $path): void
    {
        self::instance()->addPath($path, self::PRESETS);
    }

    public static function addBlocksPath(string $path): void
    {
        self::instance()->addPath($path, self::BLOCKS);
    }

    public static function addSetsPath(string $path): void
    {
        self::instance()->addPath($path, self::SETS);
    }

    public static function removePath(string $path, string $type): void
    {
        if (!self::isValidPathType($type)) {
            throw new Exception("Unknown path type '{$type}'");
        }

        if (($key = array_search($path, self::instance()->paths[$type])) !== false) {
            unset(self::instance()->paths[$type][$key]);
        }
    }

    public static function removePresetsPath(string $path): void
    {
        self::instance()->removePath($path, self::PRESETS);
    }

    public static function removeBlocksPath(string $path): void
    {
        self::instance()->removePath($path, self::BLOCKS);
    }

    public static function removeSetsPath(string $path): void
    {
        self::instance()->removePath($path, self::SETS);
    }

    public static function appendNamespace(string $namespace): void
    {
        self::instance()->namespaces[] = $namespace;
    }

    public static function prependNamespace(string $namespace): void
    {
        array_unshift(self::instance()->namespaces, $namespace);
    }

    public static function removeNamespace(string $namespace): void
    {
        if (($key = array_search($namespace, self::instance()->namespaces)) !== false) {
            unset(self::instance()->namespaces[$key]);
        }
    }

    protected static function isValidPathType(string $type): bool
    {
        return in_array($type, [self::BLOCKS, self::PRESETS, self::SETS], true);
    }

    public function __wakeup()
    {
        throw new Exception("Cannot unserialize a singleton.");
    }

    protected function __clone()
    {
        // Singletons should not be cloneable.
    }
}
