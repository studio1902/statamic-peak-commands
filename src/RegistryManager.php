<?php

namespace Studio1902\PeakCommands;

use Exception;
use Statamic\Support\Arr;

class RegistryManager
{
    const BLOCKS = 'blocks';

    const PRESETS = 'presets';

    const SETS = 'sets';

    protected array $namespaces;

    protected array $paths;

    public function __construct()
    {
        $config = require __DIR__.'/../config/statamic-peak-commands.php';

        $this->namespaces = config()->array('statamic-peak-commands.namespaces', Arr::get($config, 'namespaces', []));

        $this->paths = array_merge(
            Arr::get($config, 'paths', []),
            config()->array('statamic-peak-commands.paths', [])
        );
    }

    public function getNamespaces(): array
    {
        return $this->namespaces;
    }

    public function getPaths(): array
    {
        return $this->paths;
    }

    public function addPresetsPath(string $path): void
    {
        $this->addPath($path, self::PRESETS);
    }

    public function addBlocksPath(string $path): void
    {
        $this->addPath($path, self::BLOCKS);
    }

    public function addSetsPath(string $path): void
    {
        $this->addPath($path, self::SETS);
    }

    public function removePresetsPath(string $path): void
    {
        $this->removePath($path, self::PRESETS);
    }

    public function removeBlocksPath(string $path): void
    {
        $this->removePath($path, self::BLOCKS);
    }

    public function removeSetsPath(string $path): void
    {
        $this->removePath($path, self::SETS);
    }

    public function appendNamespace(string $namespace): void
    {
        $this->namespaces[] = $namespace;
    }

    public function prependNamespace(string $namespace): void
    {
        $this->namespaces = Arr::prepend($this->namespaces, $namespace);
    }

    public function removeNamespace(string $namespace): void
    {
        if (($key = array_search($namespace, $this->namespaces)) !== false) {
            Arr::forget($this->namespaces, $key);
        }
    }

    protected function removePath(string $path, string $type): void
    {
        if (! $this->isValidPathType($type)) {
            throw new Exception("Unknown path type '$type'");
        }

        if (($key = array_search($path, $this->paths[$type])) !== false) {
            Arr::forget($this->paths[$type], $key);
        }
    }

    protected function addPath(string $path, string $type): void
    {
        if (! $this->isValidPathType($type)) {
            throw new Exception("Unknown path type '$type'");
        }

        $this->paths[$type][] = $path;
    }

    protected function isValidPathType(string $type): bool
    {
        return in_array($type, [self::BLOCKS, self::PRESETS, self::SETS], true);
    }
}
