<?php

namespace Studio1902\PeakCommands\Commands;

use Illuminate\Console\Command;
use Statamic\Console\RunsInPlease;
use Studio1902\PeakCommands\Commands\Traits\CanClearCache;
use Studio1902\PeakCommands\Commands\Traits\HandleWithCatch;
use Studio1902\PeakCommands\Commands\Traits\NeedsValidLicense;
use Studio1902\PeakCommands\Models\Installable;
use Studio1902\PeakCommands\Models\Taxonomy;

use function Laravel\Prompts\info;

class MakeTaxonomy extends Command
{
    use CanClearCache, HandleWithCatch, NeedsValidLicense, RunsInPlease;

    protected $name = 'statamic:peak:make:taxonomy';

    protected $description = 'Make a taxonomy.';

    protected array $operations = [];

    protected Taxonomy $model;

    public function handleWithCatch(): void
    {
        $this->checkLicense();

        $this->createModel();
        $this->createConfiguration();
        $this->createBlueprint();
        $this->attachCollections();
        $this->grantPermissions();

        $this->runOperations();

        info("[✓] Taxonomy '{$this->model->name}' created.");

        $this->clearCache();
    }

    protected function createModel(): void
    {
        $this->model = app(Taxonomy::class);
    }

    protected function createConfiguration(): void
    {
        $this->operations[] = [
            'type' => 'copy',
            'input' => 'stubs/taxonomy.yaml.stub',
            'output' => 'content/taxonomies/{{ handle }}.yaml',
            'replacements' => [
                '{{ taxonomy_name }}' => $this->model->name,
            ],
        ];
    }

    protected function createBlueprint(): void
    {
        $this->operations[] = [
            'type' => 'copy',
            'input' => 'stubs/taxonomy_blueprint.yaml.stub',
            'output' => 'resources/blueprints/taxonomies/{{ handle }}/{{ handle }}.yaml',
            'replacements' => [
                '{{ taxonomy_name }}' => $this->model->name,
            ],
        ];
    }

    protected function attachCollections(): void
    {
        $this->operations[] = [
            'type' => 'attach_taxonomy_to_collections',
            'taxonomy' => $this->model->filename,
            'collections' => $this->model->collections,
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

    protected function grantPermissions(): void
    {
        if (! $this->model->grantPermissions) {
            return;
        }

        $this->operations[] = [
            'type' => 'update_role',
            'role' => 'editor',
            'permissions' => [
                'view {{ handle }} terms',
                'edit {{ handle }} terms',
                'create {{ handle }} terms',
                'delete {{ handle }} terms',
            ],
        ];
    }
}
