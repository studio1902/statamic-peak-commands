<?php

namespace Studio1902\PeakCommands\Commands;

use Illuminate\Console\Command;
use Statamic\Console\RunsInPlease;
use Studio1902\PeakCommands\Commands\Traits\CanClearCache;
use Studio1902\PeakCommands\Commands\Traits\HandleWithCatch;
use Studio1902\PeakCommands\Commands\Traits\NeedsValidLicense;
use Studio1902\PeakCommands\Models\Installable;
use Studio1902\PeakCommands\Models\Nav;
use function Laravel\Prompts\info;

class MakeNav extends Command
{
    use RunsInPlease, NeedsValidLicense, CanClearCache, HandleWithCatch;

    protected $name = 'statamic:peak:make:nav';
    protected $description = "Make a navigation.";

    protected array $operations = [];
    protected ?Nav $model = null;

    public function handleWithCatch(): void
    {
        $this->checkLicense();

        $this->createModel();
        $this->createConfiguration();
        $this->createBlueprint();
        $this->attachCollections();
        $this->grantPermissions();

        $this->runOperations();
        $this->clearCache();

        info("<info>[✓]</info> Navigation '{$this->model->name}' created.");
    }

    protected function createConfiguration(): void
    {
        $this->operations[] = [
            'type' => 'copy',
            'input' => 'navigation.yaml.stub',
            'output' => "content/navigation/{{ handle }}.yaml",
            'replacements' => [
                '{{ navigation_name }}' => $this->model->name,
                '{{ max_depth }}' => $this->model->maxDepth,
            ]
        ];
    }

    protected function createBlueprint(): void
    {
        $this->operations[] = [
            'type' => 'copy',
            'input' => 'navigation_blueprint.yaml.stub',
            'output' => "resources/blueprints/navigation/{{ handle }}.yaml"
        ];
    }

    protected function attachCollections(): void
    {
        $this->operations[] = [
            'type' => 'attach_collections_to_navigation',
            'navigation' => $this->model->filename,
            'collections' => $this->model->collections,
        ];
    }

    protected function createModel(): void
    {
        $this->model = app(Nav::class);
    }

    protected function grantPermissions(): void
    {
        if (!$this->model->grantPermissions) {
            return;
        }

        $this->operations[] = [
            'type' => 'update_role',
            'role' => 'editor',
            'permissions' => [
                "view {{ handle }} nav",
                "edit {{ handle }} nav",
            ],
        ];
    }

    protected function runOperations(): void
    {
        app()
            ->make(Installable::class, [
                'config' => [
                    'name' => $this->model->name,
                    'handle' => $this->model->filename,
                    'operations' => $this->operations,
                    'path' => base_path('vendor/studio1902/statamic-peak-commands/resources/stubs'),
                ]
            ])
            ->install();
    }
}
