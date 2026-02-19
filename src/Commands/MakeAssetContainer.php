<?php

namespace Studio1902\PeakCommands\Commands;

use Illuminate\Console\Command;
use Statamic\Console\RunsInPlease;
use Studio1902\PeakCommands\Commands\Traits\CanClearCache;
use Studio1902\PeakCommands\Commands\Traits\HandleWithCatch;
use Studio1902\PeakCommands\Commands\Traits\NeedsValidLicense;
use Studio1902\PeakCommands\Models\AssetContainer;
use Studio1902\PeakCommands\Models\Installable;

use function Laravel\Prompts\info;
use function Laravel\Prompts\pause;
use function Laravel\Prompts\warning;

class MakeAssetContainer extends Command
{
    use CanClearCache, HandleWithCatch, NeedsValidLicense, RunsInPlease;

    protected $name = 'statamic:peak:make:asset-container';

    protected $description = 'Make an asset container.';

    protected array $operations = [];

    protected AssetContainer $model;

    public function handleWithCatch(): void
    {
        $this->checkLicense();

        $this->createModel();
        $this->createConfiguration();
        $this->grantPermissions();

        $this->runOperations();

        warning("Add this to your filesystems.php:\n\n'{$this->model->filename}' => [\n\t'driver' => 'local',\n\t'root' => public_path('{$this->model->filename}'),\n\t'url' => '\\{$this->model->filename}',\n\t'visibility' => 'public',\n\t'throw' => false,\n\t'report' => false,\n],");
        pause('Follow the instructions and press ENTER to continue.');
        info("[✓] Asset container '{$this->model->name}' created.");

        $this->clearCache();
    }

    protected function createConfiguration(): void
    {
        $this->operations[] = [
            'type' => 'copy',
            'input' => 'stubs/asset_container.yaml.stub',
            'output' => 'content/assets/{{ handle }}.yaml',
            'replacements' => [
                '{{ asset_container_name }}' => $this->model->name,
                '{{ handle }}' => $this->model->filename,
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
        $this->model = app(AssetContainer::class);
    }
}
