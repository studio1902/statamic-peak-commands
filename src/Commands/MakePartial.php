<?php

namespace Studio1902\PeakCommands\Commands;

use Illuminate\Console\Command;
use Statamic\Console\RunsInPlease;
use Studio1902\PeakCommands\Commands\Traits\HandleWithCatch;
use Studio1902\PeakCommands\Commands\Traits\NeedsValidLicense;
use Studio1902\PeakCommands\Models\Installable;
use Studio1902\PeakCommands\Models\Partial;

use function Laravel\Prompts\info;

class MakePartial extends Command
{
    use HandleWithCatch, NeedsValidLicense, RunsInPlease;

    protected $name = 'statamic:peak:make:partial';

    protected $description = 'Make a partial with IDE hinting and template paths.';

    protected array $operations = [];

    protected Partial $model;

    public function handleWithCatch(): void
    {
        $this->checkLicense();

        $this->createModel();
        $this->createTemplate();

        $this->runOperations();

        info("<info>[✓]</info> {$this->model->type} '{$this->model->filename}' added.");
    }

    protected function createModel(): void
    {
        $this->model = app(Partial::class);
    }

    protected function createTemplate(): void
    {
        $this->operations[] = [
            'type' => 'copy',
            'input' => 'stubs/partial.antlers.html.stub',
            'output' => "resources/views/{$this->model->folder}/_{$this->model->filename}.antlers.html",
            'replacements' => [
                '{{ partial_name }}' => $this->model->name,
                '{{ partial_description }}' => $this->model->description,
                '{{ folder }}' => $this->model->folder,
            ],
        ];
    }

    protected function runOperations(): void
    {
        app(Installable::class, [
            'config' => [
                'name' => $this->model->name,
                'handle' => $this->model->filename,
                'operations' => $this->operations,
                'base_path' => base_path('vendor/studio1902/statamic-peak-commands/resources'),
            ],
        ])->install();
    }
}
