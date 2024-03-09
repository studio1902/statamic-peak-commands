<?php

namespace Studio1902\PeakCommands\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Statamic\Console\RunsInPlease;
use Statamic\Facades\Config;
use Statamic\Facades\Entry;
use Statamic\Support\Arr;
use Symfony\Component\Yaml\Yaml;
use Stringy\StaticStringy as Stringy;
use function Laravel\Prompts\confirm;
use function Laravel\Prompts\search;
use function Laravel\Prompts\select;
use function Laravel\Prompts\text;

class AddCollection extends Command
{
    use RunsInPlease, SharedFunctions, NeedsValidLicense;

    protected $name = 'statamic:peak:add-collection';
    protected $description = "Add a collection.";
    protected $collection_name = '';
    protected $filename = '';
    protected $public = false;
    protected $slugs = true;
    protected $mount_collection = false;
    protected $route = '';
    protected $add_page = false;
    protected $page_title = '';
    protected $layout = '';
    protected $revisions = false;
    protected $dated = false;
    protected $sort_dir = '';
    protected $date_past = 'public';
    protected $date_future = 'private';
    protected $template = '';
    protected $mount = '';
    protected $index = false;
    protected $show = false;
    protected $permissions = true;

    public function handle()
    {
        $this->checkLicense();

        $this->collection_name = text(
            label: 'What should be the name for this collection?',
            placeholder: 'E.g. News',
            required: true
        );

        $this->filename = Str::slug($this->collection_name, '_');

        $this->public = confirm(
            label: 'Should this be a public collection with a route?',
            default: true
        );

        if (! $this->public) {
            $this->slugs = confirm(
                label: 'Do you want to require slugs?',
                default: true
            );
        }

        if ($this->public) {
            $this->mount_collection = confirm(
                label: 'Do you want to mount this collection on an entry?',
                default: true
            );

            if ($this->mount_collection) {
                $this->add_page = confirm(
                    label: 'Do you want to create a new page to mount this collection on?',
                    default: true
                );

                if ($this->add_page) {
                    $this->page_title = text(
                        label: 'What should be the page title for this mount?',
                        placeholder: 'E.g. News',
                        required: true
                    );
                    $this->mount = $this->addPage();
                } else {
                    $choice = search(
                        label: 'On which page existing page do you want to mount this collection?',
                        options: function (string $value) {
                            if (!$value) {
                                return collect($this->getPages())
                                    ->values()
                                    ->all();
                            }

                            return collect($this->getPages())
                                ->filter(fn(string $item) => Str::contains($item, $value, true))
                                ->values()
                                ->all();
                        },
                        required: true
                    );
                    preg_match('/\[(.*?)\]/', $choice, $id);
                    $this->mount = $id[1];
                }
            }
            $this->route = text(
                label: 'What should be the route for this collection?',
                default: '/{mount}/{slug}',
                required: true
            );
        }

        $this->layout = text(
            label: 'What should be the layout file for this collection?',
            default: 'layout',
            required: true
        );

        $this->revisions = confirm(
            label: 'Should revisions be enabled?',
            default: false
        );

        $this->sort_dir = select(
            label: 'What should the sort direction be?',
            options: [
                'asc' => 'Ascending',
                'desc' => 'Descending',
            ],
            default: 'asc'
        );

        $this->dated = confirm(
            label: 'Should this be a dated collection?',
            default: false
        );

        if ($this->dated) {
            $this->date_past = select(
                label: 'What should be the date behavior for entries in the past?',
                options: [
                    'public' => 'Public',
                    'private' => 'Private',
                ],
                default: 'public'
            );

            $this->date_future = select(
                label: 'What should be the date behavior for entries in the future?',
                options: [
                    'public' => 'Public',
                    'private' => 'Private',
                ],
                default: 'private'
            );
        }

        if ($this->public && $this->mount) {
            $this->index = confirm(
                label: 'Generate and apply index template?',
                default: true
            );
        }

        if ($this->public) {
            $this->show = confirm(
                label: 'Generate and apply show template?',
                default: true
            );
        }

        $this->permissions = confirm(
            label: 'Grant edit permissions to editor role?',
            default: true
        );

        try {
            $this->createCollection();
            $this->createDirectory("resources/blueprints/collections/{$this->filename}");
            $this->createBlueprint();
            if ($this->index || $this->show) $this->createDirectory("resources/views/{$this->filename}");
            if ($this->index) $this->createIndexTemplate();
            if ($this->index) $this->setIndexTemplate();
            if ($this->mount) $this->installAndSetIndexContentBlock();
            if ($this->show) $this->createShowTemplate();
            if ($this->permissions) $this->grantPermissionsToEditor();
            $this->widgetNotice();
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }

        Artisan::call('cache:clear');

        $this->info("<info>[✓]</info> Collection '{$this->collection_name}' created.");
    }

    /**
     * Get all pages.
     *
     * @return array
     */
    protected function getPages()
    {
        return Entry::query()
            ->where('collection', 'pages')
            ->where('status', 'published')
            ->orderBy('title', 'asc')
            ->get()
            ->map(fn($entry) =>
               "{$entry->get('title')} [{$entry->id()}]"
            )
            ->toArray();
    }

    /**
     * Create fieldset.
     *
     * @return bool|null
     */
    protected function createCollection()
    {
        $this->checkExistence('Collection', "content/collections/{$this->filename}.yaml");

        $stub = $this->getStub('/collection.yaml.stub');
        $contents = Str::of($stub)
            ->replace('{{ collection_name }}', $this->collection_name)
            ->replace('{{ route }}', $this->route)
            ->replace('{{ layout }}', $this->layout)
            ->replace('{{ revisions }}', ($this->revisions) ? 'true' : 'false')
            ->replace('{{ sort_dir }}', $this->sort_dir)
            ->replace('{{ dated }}', ($this->dated) ? 'true' : 'false')
            ->replace('{{ date_past }}', $this->date_past)
            ->replace('{{ date_future }}', $this->date_future)
            ->replace('{{ template }}', $this->show ? "{$this->filename}/show" : 'default' )
            ->replace('{{ mount }}', $this->mount)
            ->replace('{{ slugs }}', ($this->slugs) ? 'true' : 'false');

        File::put(base_path("content/collections/{$this->filename}.yaml"), $contents);
    }

    /**
     * Create blueprints.
     *
     * @return bool|null
     */
    protected function createBlueprint()
    {
        $this->checkExistence('Blueprint', "resources/blueprints/collections/{$this->filename}/{$this->filename}.yaml");

        $append = !$this->slugs ? '_no_slug' : '';
        $stub = ($this->public)
            ? ($this->dated
                ? "/collection_blueprint_public_dated{$append}.yaml.stub"
                : "/collection_blueprint_public{$append}.yaml.stub")
            : ($this->dated
                ? "/collection_blueprint_private_dated{$append}.yaml.stub"
                : "/collection_blueprint_private{$append}.yaml.stub");

        $stub = $this->getStub($stub);
        $contents = Str::of($stub)
            ->replace('{{ collection_name }}', $this->collection_name);

        File::put(base_path("resources/blueprints/collections/{$this->filename}/{$this->filename}.yaml"), $contents);
    }

    /**
     * Create dir.
     *
     * @return bool|null
     */
    protected function createDirectory($directory)
    {
        File::makeDirectory($directory);
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
    protected function addPage()
    {
        $entry = Entry::make()
            ->collection('pages')
            ->published(true)
            ->slug(Stringy::slugify($this->page_title, '-', Config::getShortLocale()))
            ->data(['title' => $this->page_title]);
        $entry->save();

        return $entry->id();
    }

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

    /**
     * Grant permissions to editor.
     *
     * @return bool|null
     */
    protected function grantPermissionsToEditor()
    {
        $roles = Yaml::parseFile(base_path('resources/users/roles.yaml'));
        $newPermissions = [
            "view {$this->filename} entries",
            "edit {$this->filename} entries",
            "create {$this->filename} entries",
            "delete {$this->filename} entries",
            "publish {$this->filename} entries",
            "reorder {$this->filename} entries",
            "edit other authors {$this->filename} entries",
            "publish other authors {$this->filename} entries",
            "delete other authors {$this->filename} entries",
        ];

        $existingPermissions = Arr::get($roles, 'editor.permissions');
        $permissions = array_merge($existingPermissions, $newPermissions);

        Arr::set($roles, 'editor.permissions', $permissions);

        File::put(base_path('resources/users/roles.yaml'), Yaml::dump($roles, 99, 2));
    }

    /**
     * Display CP widget notice.
     *
     * @return bool|null
     */
    protected function widgetNotice()
    {
        $this->warn("Add this to your `config/statamic/cp.php` widgets array:\n\n[\n\t'type' => 'collection',\n\t'collection' => '$this->filename',\n\t'width' => 50\n],");
        $this->newLine();
    }
}
