<?php

namespace Studio1902\PeakCommands\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
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
    use RunsInPlease, NeedsValidLicense, CanClearCache, HandleWithCatch;

    protected $name = 'statamic:peak:make:collection';
    protected $description = "Make a collection.";
    protected array $operations = [];
    protected ?Collection $model = null;

    public function handleWithCatch()
    {
        $this->checkLicense();

        $this->createModel();
        $this->createConfiguration();
        $this->createBlueprint();
        //TODO[mr]: here (08.04.2025 mr)
        $this->grantPermissions();

        $this->runOperations();
        $this->clearCache();
        $this->showWidgetNotice();

        info("<info>[✓]</info> Collection '{$this->model->name}' created.");
        return;
        try {
            if ($this->index || $this->show) File::makeDirectory("resources/views/{$this->filename}");
            if ($this->index) $this->createIndexTemplate();
            if ($this->index) $this->setIndexTemplate();
            if ($this->mount) $this->installAndSetIndexContentBlock();
            if ($this->show) $this->createShowTemplate();
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }

    protected function createBlueprint(): void
    {
        $publicPathPart = $this->model->public ? '_public' : '_private';
        $datedPathPart = $this->model->dated ? '_dated' : '';
        $slugPathPart = $this->model->slugs ? '' : '_no_slug';

        $this->operations[] = [
            'type' => 'copy',
            'input' => "/collection_blueprint{$publicPathPart}{$datedPathPart}{$slugPathPart}.yaml.stub",
            'output' => "resources/blueprints/collections/{{ handle }}/{{ handle }}.yaml",
            'replacements' => [
                '{{ collection_name }}' => $this->model->name,
            ]
        ];
    }

    /**
     * Create index template.
     *
     * @return bool|null
     */
    protected function createIndexTemplate()
    {
        $this->checkExistence('Template', "resources/views/{$this->filename}/index.antlers.html");

        $stub = $this->getStub('/index.antlers.html.stub');
        $contents = Str::of($stub)
            ->replace('{{ collection_name }}', $this->collection_name)
            ->replace('{{ handle }}', $this->filename)
            ->replace('{{ filename }}', $this->filename)
            ->replace('{{ sort }}', $this->dated ? 'date:desc' : 'title');

        File::put(base_path("resources/views/{$this->filename}/index.antlers.html"), $contents);
    }

    /**
     * Create index template.
     *
     * @return bool|null
     */
    protected function createShowTemplate()
    {
        $this->checkExistence('Template', "resources/views/{$this->filename}/show.antlers.html");

        $stub = $this->getStub('/show.antlers.html.stub');
        $contents = Str::of($stub)
            ->replace('{{ collection_name }}', $this->collection_name)
            ->replace('{{ filename }}', $this->filename);

        File::put(base_path("resources/views/{$this->filename}/show.antlers.html"), $contents);
    }

    /**
     * Add a page.
     *
     * @return string
     */
    /**
     * Set index template.
     *
     * @return bool|null
     */
    protected function setIndexTemplate()
    {
        Entry::find($this->mount)
            ->set('template', "{$this->filename}/index")
            ->save();
    }

    /**
     * Install Index Content page builder block and put it on the mount.
     *
     * @return null
     */
    protected function installAndSetIndexContentBlock()
    {
        File::put(base_path("resources/fieldsets/index_content.yaml"), $this->getStub('/blocks/index_content.yaml.stub'));
        File::put(base_path("resources/views/page_builder/_index_content.antlers.html"), $this->getStub('/blocks/index_content.antlers.html.stub'));
        $this->updatePageBuilder('Index content', 'Render the currently mounted entries if available.', 'file-content-list', 'index_content');

        $pageBuilder = Entry::find($this->mount)
            ->get('page_builder');
        $pageBuilder[] = [
            'type' => 'index_content',
            'enabled' => 'true'
        ];

        Entry::find($this->mount)
            ->set('page_builder', $pageBuilder)
            ->save();
    }

    protected function showWidgetNotice()
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
            'input' => 'collection.yaml.stub',
            'output' => "content/collections/{{ handle }}.yaml",
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
            ]
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

    protected function grantPermissions(): void
    {
        if (!$this->model->grantPermissions) {
            return;
        }

        $this->operations[] = [
            'type' => 'update_role',
            'role' => 'editor',
            'permissions' => [
                "view {{ handle }} entries",
                "edit {{ handle }} entries",
                "create {{ handle }} entries",
                "delete {{ handle }} entries",
                "publish {{ handle }} entries",
                "reorder {{ handle }} entries",
                "edit other authors {{ handle }} entries",
                "publish other authors {{ handle }} entries",
                "delete other authors {{ handle }} entries",
            ],
        ];
    }
}
