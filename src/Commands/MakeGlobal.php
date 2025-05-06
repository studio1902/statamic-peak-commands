<?php

namespace Studio1902\PeakCommands\Commands;

use Illuminate\Console\Command;
use Statamic\Console\RunsInPlease;
use Studio1902\PeakCommands\Commands\Traits\CanClearCache;
use Studio1902\PeakCommands\Commands\Traits\HandleWithCatch;
use Studio1902\PeakCommands\Commands\Traits\NeedsValidLicense;
use Studio1902\PeakCommands\Models\Globals;
use Studio1902\PeakCommands\Models\Installable;

use function Laravel\Prompts\info;

class MakeGlobal extends Command
{
    use CanClearCache, HandleWithCatch, NeedsValidLicense, RunsInPlease;

    protected $name = 'statamic:peak:make:global';

    protected $description = 'Make a global set.';

    protected array $operations = [];

    protected Globals $model;

    public function handleWithCatch(): void
    {
        $this->checkLicense();

        $this->createModel();
        $this->createConfiguration();
        $this->createBlueprint();
        $this->grantPermissions();

        $this->runOperations();

        info("[✓] Global '{$this->model->name}' created.");

        $this->clearCache();
    }

    protected function createConfiguration(): void
    {
        $this->operations[] = [
            'type' => 'copy',
            'input' => 'stubs/global.yaml.stub',
            'output' => 'content/globals/{{ handle }}.yaml',
            'replacements' => [
                '{{ global_name }}' => $this->model->name,
            ],
        ];
    }

    protected function createBlueprint(): void
    {
        $this->operations[] = [
            'type' => 'copy',
            'input' => 'stubs/global_blueprint.yaml.stub',
            'output' => 'resources/blueprints/globals/{{ handle }}.yaml',
            'replacements' => [
                '{{ global_name }}' => $this->model->name,
            ],
        ];
    }

    protected function grantPermissions(): void
    {
        if (! $this->model->grantPermissions) {
            return;
        }

        $this->operations[] = [
            'type' => 'update_role',
            'role' => 'editor',
            'permissions' => [
                'edit {{ handle }} globals',
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

    protected function createModel(): void
    {
        $this->model = app(Globals::class);
    }
}
