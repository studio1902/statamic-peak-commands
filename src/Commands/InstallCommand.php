<?php

namespace Studio1902\PeakCommands\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Studio1902\PeakCommands\Commands\Traits\CanClearCache;
use Studio1902\PeakCommands\Commands\Traits\NeedsValidLicense;
use Studio1902\PeakCommands\Models\Installable;
use Studio1902\PeakCommands\Registry;

use function Laravel\Prompts\multisearch;
use function Laravel\Prompts\warning;

abstract class InstallCommand extends Command
{
    use CanClearCache, NeedsValidLicense;

    protected array $choices = [];

    protected ?Collection $items = null;

    protected string $type;

    protected function collectChoices(string $label, string $emptyValidation): void
    {
        if (! $this->items || $this->items->isEmpty()) {
            warning("No {$this->type} found in provided paths.");
            exit();
        }

        $options = $this->collectOptions();

        $this->choices = multisearch(
            label: $label,
            options: fn (string $value) => strlen($value) > 0
                ? $options->filter(fn (string $item) => Str::contains($item, $value, true))->toArray()
                : $options->toArray(),
            scroll: 15,
            validate: fn ($values) => match (true) {
                empty($values) => $emptyValidation,
                default => null,
            }
        );
    }

    protected function collectOptions(): Collection
    {
        return $this->items->mapWithKeys(fn (array $item) => [$item['handle'] => "{$item['name']}: {$item['description']}"]);
    }

    protected function loadItems(): void
    {
        $this->items = collect(Registry::getPaths()[$this->type])
            ->map(fn ($path) => \Statamic\Support\Str::ensureRight($path, DIRECTORY_SEPARATOR))
            ->flatMap(fn (string $path) => File::glob($path.'*/config.php'))
            ->unique()
            ->map(fn (string $path) => collect(['path' => \Statamic\Support\Str::removeRight($path, DIRECTORY_SEPARATOR.'config.php')])
                ->merge(include $path)
                ->sort()
                ->all()
            )
            ->mapWithKeys(fn (array $preset) => [$preset['handle'] => $preset]);
    }

    protected function installChoices(\Closure $successCallback): void
    {
        collect($this->choices)->each(
            fn (string $handle) => $successCallback(
                app(Installable::class, ['config' => $this->items->get($handle)])->install()
            )
        );
    }

    protected function handleInstallation(string $label, string $emptyValidation, \Closure $successMessage): void
    {
        $this->checkLicense();

        $this->loadItems();

        $this->collectChoices($label, $emptyValidation);

        $this->installChoices($successMessage);

        $this->clearCache();
    }
}
