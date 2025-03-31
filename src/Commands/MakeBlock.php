<?php

namespace Studio1902\PeakCommands\Commands;

use Illuminate\Console\Command;
use Statamic\Console\RunsInPlease;
use Studio1902\PeakCommands\Commands\Traits\NeedsValidLicense;
use Studio1902\PeakCommands\Models\Block;
use Studio1902\PeakCommands\Models\Installable;
use Studio1902\PeakCommands\Operations\Traits\CanPickIcon;
use function Laravel\Prompts\info;

class MakeBlock extends Command
{
    use RunsInPlease, NeedsValidLicense, CanPickIcon;

    protected $name = 'statamic:peak:make:block';
    protected $description = "Make a page builder block.";

    protected array $operations = [];
    protected ?Block $model;

    public function handle(): void
    {
        $this->checkLicense();
        $this->createModel();
        $this->createTemplate();
        $this->createFieldset();
        $this->updatePageBuilder();

        $this->runOperations();

        info("<info>[✓]</info> Peak page builder block '{$this->model->name}' added.");
    }

    protected function createModel(): void
    {
        $this->model = app()->make(Block::class);
    }

    protected function createTemplate(): void
    {
        $this->operations[] = [
            'type' => 'copy',
            'input' => 'block.antlers.html.stub',
            'output' => 'resources/views/page_builder/_{{ handle }}.antlers.html'
        ];
    }

    protected function createFieldset(): void
    {
        $this->operations[] = [
            'type' => 'copy',
            'input' => 'fieldset_block.yaml.stub',
            'output' => 'resources/fieldsets/{{ handle }}.yaml'
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
        app()
            ->make(Installable::class, [
                'config' => [
                    'name' => $this->model->name,
                    'handle' => $this->model->handle,
                    'operations' => $this->operations,
                    'path' => base_path('vendor/studio1902/statamic-peak-commands/resources/stubs'),
                ]
            ])
            ->install();
    }
}
