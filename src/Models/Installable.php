<?php

namespace Studio1902\PeakCommands\Models;

use Statamic\Facades\Config;
use Statamic\Support\Arr;
use Statamic\Support\Str;
use Studio1902\PeakCommands\Operations\Operation;

class Installable
{
    public string $name;

    public string $handle;

    public string $basePath;

    public array $operations;

    public string $singularName;

    public string $renameHandle;

    public string $renameName;

    public string $renameSingularName;

    public string $filepath;

    public string $renameSingularHandle;

    public function __construct(array $config)
    {
        $this->name = Arr::get($config, 'name');
        $this->handle = Arr::get($config, 'handle');
        $this->basePath = Arr::get($config, 'base_path');
        $this->operations = Arr::get($config, 'operations');
        $this->singularName = Arr::get($config, 'singular_name', '');

        // Extract filepath if handle contains directory
        $this->extractFilepath();

        // Apply fallbacks
        $this->renameName = $this->name;
        $this->renameHandle = $this->handle;
        $this->renameSingularName = $this->singularName;
        $this->renameSingularHandle = Str::slug($this->renameSingularName, '_', Config::getShortLocale());
    }

    public function install(): self
    {
        return collect($this->operations)
            ->map(fn (array $operation) => Operation::resolve(Arr::get($operation, 'type'), $operation))
            ->reduce(fn (Installable $installable, Operation $operation) => $operation->hydrate($installable)->run(), $this);
    }

    protected function extractFilepath(): void
    {
        $full = collect(explode('/', $this->handle));

        $this->handle = $full->pop();

        $this->filepath = $full->count() > 0 ? $full->join('/').'/' : '';
    }
}
