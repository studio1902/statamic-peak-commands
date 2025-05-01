<?php

namespace Studio1902\PeakCommands\Models;

use Illuminate\Support\Str;
use Statamic\Facades\Collection;
use Statamic\Facades\Config;
use Statamic\Support\Arr;
use Stringy\StaticStringy as Stringy;

use function Laravel\Prompts\confirm;
use function Laravel\Prompts\multisearch;
use function Laravel\Prompts\text;

class Nav
{
    public string $name;

    public string $filename;

    public int $maxDepth;

    public array $collections;

    public bool $grantPermissions;

    public function __construct(array $config = [])
    {
        $this->name = Arr::get($config, 'name') ?? $this->promptForName();
        $this->maxDepth = Arr::get($config, 'max_depth') ?? $this->promptForMaxDepth();
        $this->collections = Arr::get($config, 'collections') ?? $this->promptForCollections();
        $this->grantPermissions = Arr::get($config, 'permissions') ?? $this->promptForPermissions();
        $this->filename = $this->generateFilename();
    }

    protected function promptForName(): string
    {
        return text(
            label: 'What should be the name for this navigation?',
            placeholder: 'E.g. Actions',
            required: true
        );
    }

    protected function generateFilename(): string
    {
        return Stringy::slugify($this->name, '_', Config::getShortLocale());
    }

    protected function promptForCollections(): array
    {
        $options = collect(Collection::all())->pluck('title', 'handle');

        return multisearch(
            label: 'Enable linking to entries from these collections:',
            options: fn (string $value) => strlen($value) > 0
                ? $options->filter(fn (string $item) => Str::contains($item, $value, true))->toArray()
                : $options->toArray(),
            scroll: 15
        );
    }

    protected function promptForPermissions(): bool
    {
        return confirm(
            label: 'Grant edit permissions to editor role?',
            default: true
        );
    }

    protected function promptForMaxDepth(): int
    {
        return (int) text(
            label: 'What should be the max depth for this navigation?',
            placeholder: '2',
            validate: ['name' => 'required|int|numeric']
        );
    }
}
