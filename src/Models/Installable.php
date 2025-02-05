<?php

namespace Studio1902\PeakCommands\Models;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Statamic\Facades\Config;
use Studio1902\PeakCommands\Operations\Operation;

class Installable
{
    public string $name;
    public string $handle;
    public string $path;
    public array $operations;
    public string $singularName;

    public string $renameHandle;
    public string $renameName;
    public string $renameSingularName;
    public string $renameSingularHandle;

    public function __construct(array $config)
    {
        $this->name = $config['name'];
        $this->handle = $config['handle'];
        $this->path = $config['path'];
        $this->operations = $config['operations'];
        $this->singularName = $config['singular_name'] ?? '';

        // Apply fallbacks
        $this->renameName = $this->name;
        $this->renameHandle = $this->handle;
        $this->renameSingularName = $this->singularName;
        $this->renameSingularHandle = Str::slug($this->renameSingularName, '_', Config::getShortLocale());
    }

    public function install(): self
    {
        return collect($this->operations)
            ->map(fn(array $operation) => Operation::resolve(Arr::get($operation, 'type'), $operation))
            ->reduce(fn(Installable $installable, Operation $operation) => $operation->hydrate($installable)->run(), $this);
    }
}
