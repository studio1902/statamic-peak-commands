<?php

namespace Studio1902\PeakCommands\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Statamic\Console\RunsInPlease;
use Statamic\Facades\Collection;
use Statamic\Support\Arr;
use Studio1902\PeakCommands\Commands\Traits\NeedsValidLicense;
use Symfony\Component\Yaml\Yaml;
use function Laravel\Prompts\confirm;
use function Laravel\Prompts\multisearch;
use function Laravel\Prompts\text;

class MakeNav extends Command
{
    use RunsInPlease, SharedFunctions, NeedsValidLicense;

    protected $name = 'statamic:peak:make:nav';
    protected $description = "Make a navigation.";
    protected $navigation_name = '';
    protected $filename = '';
    protected $max_depth = 1;
    protected $collections = [];
    protected $permissions = true;

    public function handle()
    {
        $this->checkLicense();

        $this->navigation_name = text(
            label: 'What should be the name for this navigation?',
            placeholder: 'E.g. Actions',
            required: true
        );

        $this->filename = Str::slug($this->navigation_name, '_');

        $this->max_depth = text(
            label: 'What should be the max depth for this navigation?',
            placeholder: '2',
            validate: ['name' => 'required|int|numeric']
        );

        $options = collect(Collection::all())->pluck('title', 'handle');

        $this->collections = multisearch(
            label: "Enable linking to entries from these collections:",
            options: fn (string $value) => strlen($value) > 0
                ? $options->filter(fn(string $item) => Str::contains($item, $value, true))->toArray()
                : $options->toArray(),
            scroll: 15
        );

        $this->permissions = confirm(
            label: 'Grant edit permissions to editor role?',
            default: true
        );

        try {
            $this->createNavigation();
            $this->createBlueprint();
            $this->attachCollections();
            $this->handlePermissions();
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }

        Artisan::call('cache:clear');

        $this->info("<info>[✓]</info> Navigation '{$this->navigation_name}' created.");
    }

    /**
     * Create fieldset.
     *
     * @return bool|null
     */
    protected function createNavigation()
    {
        $this->checkExistence('Navigation', "content/navigation/{$this->filename}.yaml");

        $stub = $this->getStub('/navigation.yaml.stub');
        $contents = Str::of($stub)
            ->replace('{{ navigation_name }}', $this->navigation_name)
            ->replace('{{ max_depth }}', $this->max_depth);

        File::put(base_path("content/navigation/{$this->filename}.yaml"), $contents);
    }

    /**
     * Create blueprints.
     *
     * @return bool|null
     */
    protected function createBlueprint()
    {
        $this->checkExistence('Blueprint', "resources/blueprints/navigation/{$this->filename}.yaml");

        $contents = Str::of(Str::of($this->getStub('/navigation_blueprint.yaml.stub')));

        if (! File::exists("resources/blueprints/navigation")) File::makeDirectory("resources/blueprints/navigation");

        File::put(base_path("resources/blueprints/navigation/{$this->filename}.yaml"), $contents);
    }

    /**
     * Attach Collections.
     *
     * @return bool|null
     */
    protected function attachCollections()
    {
        if (! $this->collections) {
            return;
        }

        $nav = Yaml::parseFile(base_path("content/navigation/{$this->filename}.yaml"));

        Arr::set($nav, 'collections', $this->collections);

        File::put(base_path("content/navigation/{$this->filename}.yaml"), Yaml::dump($nav, 99, 2));
    }

    /**
     * Handle permissions
     *
     * @return bool|null
     */
    protected function handlePermissions()
    {
        if (! $this->permissions) {
            return;
        }

        $permissions = [
            "view {$this->filename} nav",
            "edit {$this->filename} nav",
        ];

        $this->grantPermissionsToEditor($permissions);
    }
}
