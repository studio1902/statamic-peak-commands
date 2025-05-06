<?php

namespace Studio1902\PeakCommands\Commands;

use Illuminate\Console\Command;
use Statamic\Console\RunsInPlease;
use Studio1902\PeakCommands\Commands\Traits\HandleWithCatch;
use Studio1902\PeakCommands\Commands\Traits\NeedsValidLicense;
use Studio1902\PeakCommands\Models\Installable;
use Studio1902\PeakCommands\Models\Set;
use Studio1902\PeakCommands\Operations\Traits\CanPickIcon;

use function Laravel\Prompts\info;

class MakeSet extends Command
{
    use CanPickIcon, HandleWithCatch, NeedsValidLicense, RunsInPlease;

    protected $name = 'statamic:peak:make:set';

    protected $description = 'Make an Article (Bard) set.';

    protected array $operations = [];

    protected Set $model;

    public function handleWithCatch(): void
    {
        $this->checkLicense();

        $this->createModel();
        $this->createTemplate();
        $this->createFieldset();
        $this->updateArticleSets();

        $this->runOperations();

        info("[✓] Peak page builder Article set '{$this->model->name}' added.");
    }

    protected function createModel(): void
    {
        $this->model = app(Set::class);
    }

    protected function createTemplate(): void
    {
        $this->operations[] = [
            'type' => 'copy',
            'input' => 'stubs/set.antlers.html.stub',
            'output' => 'resources/views/components/{{ filepath }}_{{ handle }}.antlers.html',
        ];
    }

    protected function createFieldset(): void
    {
        $this->operations[] = [
            'type' => 'copy',
            'input' => 'stubs/fieldset_set.yaml.stub',
            'output' => 'resources/fieldsets/{{ filepath }}{{ handle }}.yaml',
        ];
    }

    protected function updateArticleSets(): void
    {
        $this->operations[] = [
            'type' => 'update_article_sets',
            'set' => $this->model->toArray(),
        ];
    }

    protected function runOperations(): void
    {
        app(Installable::class, [
            'config' => [
                'name' => $this->model->name,
                'handle' => $this->model->handle,
                'operations' => $this->operations,
                'base_path' => base_path('vendor/studio1902/statamic-peak-commands/resources'),
            ],
        ])->install();
    }
}
