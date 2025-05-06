<?php

namespace Studio1902\PeakCommands\Commands;

use Illuminate\Console\Command;
use Statamic\Console\RunsInPlease;
use Statamic\Facades\Entry;
use Studio1902\PeakCommands\Commands\Traits\CanClearCache;
use Studio1902\PeakCommands\Commands\Traits\HandleWithCatch;
use Studio1902\PeakCommands\Commands\Traits\NeedsValidLicense;
use Studio1902\PeakCommands\Models\Collection;
use Studio1902\PeakCommands\Models\Installable;

use function Laravel\Prompts\info;
use function Laravel\Prompts\warning;

class MakeCollection extends Command
{
    use CanClearCache, HandleWithCatch, NeedsValidLicense, RunsInPlease;

    protected $name = 'statamic:peak:make:collection';

    protected $description = 'Make a collection.';

    protected array $operations = [];

    protected Collection $model;

    public function handleWithCatch(): void
    {
        $this->checkLicense();
        $this->createModel();
        $this->createConfiguration();
        $this->createBlueprint();
        $this->createIndexTemplate();
        $this->setIndexTemplate();
        $this->installIndexContentBlock();
        $this->createShowTemplate();
        $this->grantPermissions();

        $this->runOperations();
        $this->showWidgetNotice();

        info("[✓] Collection '{$this->model->name}' created.");

        $this->clearCache();
    }

    protected function createBlueprint(): void
    {
        $publicPathPart = $this->model->public ? '_public' : '_private';
        $datedPathPart = $this->model->dated ? '_dated' : '';
        $slugPathPart = $this->model->slugs ? '' : '_no_slug';

        $this->operations[] = [
            'type' => 'copy',
            'input' => "stubs/collection_blueprint{$publicPathPart}{$datedPathPart}{$slugPathPart}.yaml.stub",
            'output' => 'resources/blueprints/collections/{{ handle }}/{{ handle }}.yaml',
            'replacements' => [
                '{{ collection_name }}' => $this->model->name,
            ],
        ];
    }

    protected function createIndexTemplate(): void
    {
        if (! $this->model->index) {
            return;
        }

        $this->operations[] = [
            'type' => 'copy',
            'input' => 'stubs/index.antlers.html.stub',
            'output' => 'resources/views/{{ handle }}/index.antlers.html',
            'replacements' => [
                '{{ collection_name }}' => $this->model->name,
                '{{ sort }}' => $this->model->dated ? 'date:desc' : 'title',
            ],
        ];
    }

    protected function createShowTemplate(): void
    {
        if (! $this->model->show) {
            return;
        }

        $this->operations[] = [
            'type' => 'copy',
            'input' => 'stubs/show.antlers.html.stub',
            'output' => 'resources/views/{{ handle }}/show.antlers.html',
            'replacements' => [
                '{{ collection_name }}' => $this->model->name,
            ],
        ];
    }

    protected function setIndexTemplate(): void
    {
        if (! $this->model->index || ! $this->model->mount) {
            return;
        }

        Entry::find($this->model->mount)
            ->set('template', "{$this->model->filename}/index")
            ->save();
    }

    protected function installIndexContentBlock(): void
    {
        if (! $this->model->mount) {
            return;
        }

        $this->operations[] = [
            'type' => 'copy',
            'input' => 'blocks/index_content/index_content.yaml',
            'output' => 'resources/fieldsets/index_content.yaml',
        ];

        $this->operations[] = [
            'type' => 'copy',
            'input' => 'blocks/index_content/_index_content.antlers.html',
            'output' => 'resources/views/page_builder/_index_content.antlers.html',
        ];

        $this->operations[] = [
            'type' => 'update_page_builder',
            'block' => [
                'name' => 'Index content',
                'instructions' => 'Render the currently mounted entries if available.',
                'icon' => 'file-content-list',
                'handle' => 'index_content',
            ],
        ];

        $mount = Entry::find($this->model->mount);

        $pageBuilder = $mount->get('page_builder');
        $pageBuilder[] = [
            'type' => 'index_content',
            'enabled' => 'true',
        ];

        $mount->set('page_builder', $pageBuilder)->save();
    }

    protected function showWidgetNotice(): void
    {
        warning("Add this to your `config/statamic/cp.php` widgets array:\n\n[\n\t'type' => 'collection',\n\t'collection' => '{$this->model->filename}',\n\t'width' => 50\n],");
        $this->newLine();
    }

    protected function createModel(): void
    {
        $this->model = app(Collection::class);
    }

    protected function createConfiguration(): void
    {
        $this->operations[] = [
            'type' => 'copy',
            'input' => 'stubs/collection.yaml.stub',
            'output' => 'content/collections/{{ handle }}.yaml',
            'replacements' => [
                '{{ collection_name }}' => $this->model->name,
                '{{ route }}' => $this->model->route,
                '{{ layout }}' => $this->model->layout,
                '{{ revisions }}' => $this->model->revisions ? 'true' : 'false',
                '{{ sort_dir }}' => $this->model->sortDir,
                '{{ dated }}' => $this->model->dated ? 'true' : 'false',
                '{{ date_past }}' => $this->model->datePast,
                '{{ date_future }}' => $this->model->dateFuture,
                '{{ template }}' => $this->model->show ? "{$this->model->filename}/show" : 'default',
                '{{ mount }}' => $this->model->mount,
                '{{ slugs }}' => $this->model->slugs ? 'true' : 'false',
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

    protected function grantPermissions(): void
    {
        if (! $this->model->grantPermissions) {
            return;
        }

        $this->operations[] = [
            'type' => 'update_role',
            'role' => 'editor',
            'permissions' => [
                'view {{ handle }} entries',
                'edit {{ handle }} entries',
                'create {{ handle }} entries',
                'delete {{ handle }} entries',
                'publish {{ handle }} entries',
                'reorder {{ handle }} entries',
                'edit other authors {{ handle }} entries',
                'publish other authors {{ handle }} entries',
                'delete other authors {{ handle }} entries',
            ],
        ];
    }
}
