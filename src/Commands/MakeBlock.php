<?php

namespace Studio1902\PeakCommands\Commands;

use Illuminate\Console\Command;
use Statamic\Console\RunsInPlease;
use Studio1902\PeakCommands\Commands\Traits\HandleWithCatch;
use Studio1902\PeakCommands\Commands\Traits\NeedsValidLicense;
use Studio1902\PeakCommands\Models\Block;
use Studio1902\PeakCommands\Models\Installable;
use Studio1902\PeakCommands\Operations\Traits\CanPickIcon;

use function Laravel\Prompts\info;

class MakeBlock extends Command
{
    use CanPickIcon, HandleWithCatch, NeedsValidLicense, RunsInPlease;

    protected $name = 'statamic:peak:make:block';

    protected $description = 'Make a page builder block.';

    protected array $operations = [];

    protected Block $model;

    public function handleWithCatch(): void
    {
        $this->checkLicense();

        $this->createModel();
        $this->createTemplate();
        $this->createFieldset();
        $this->updatePageBuilder();

        $this->runOperations();

        info("[✓] Peak page builder block '{$this->model->name}' added.");
    }

    protected function createModel(): void
    {
        $this->model = app(Block::class);
    }

    protected function createTemplate(): void
    {
        $this->operations[] = [
            'type' => 'copy',
            'input' => 'stubs/block.antlers.html.stub',
            'output' => 'resources/views/page_builder/{{ filepath }}_{{ handle }}.antlers.html',
        ];
    }

    protected function createFieldset(): void
    {
        $this->operations[] = [
            'type' => 'copy',
            'input' => 'stubs/fieldset_block.yaml.stub',
            'output' => 'resources/fieldsets/{{ filepath }}{{ handle }}.yaml',
        ];
    }

    protected function updatePageBuilder(): void
    {
        $this->operations[] = [
            'type' => 'update_page_builder',
            'block' => $this->model->toArray(),
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
