<?php

namespace Studio1902\PeakCommands\Models;

use Illuminate\Support\Arr;
use Studio1902\PeakCommands\Operations\Operation;

class Installable
{
    public string $name = '';
    public string $handle = '';
    public string $singularName = '';
    public string $path = '';
    public array $operations = [];

    public bool $rename = false;
    public string $renameHandle = '';
    public string $renameName = '';
    public string $renameSingularName = '';
    public string $renameSingularHandle = '';

    public function __construct(array $config)
    {
        $this->name = $config['name'];
        $this->handle = $config['handle'];
        $this->singularName = $config['singular_name'] ?? '';
        $this->path = $config['path'];
        $this->operations = $config['operations'];
    }

    public function install(): self
    {
        return collect($this->operations)
            ->map(fn(array $operation) => Operation::resolve(Arr::get($operation, 'type'), $operation))
            ->reduce(fn(Installable $installable, Operation $operation) => $operation->hydrate($installable)->run(), $this);
    }
}
