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

class MakeTaxonomy extends Command
{
    use RunsInPlease, SharedFunctions, NeedsValidLicense;

    protected $name = 'statamic:peak:make:taxonomy';
    protected $description = "Make a taxonomy.";
    protected $taxonomy_name = '';
    protected $filename = '';
    protected $collections = [];
    protected $permissions = true;

    public function handle()
    {
        $this->checkLicense();

        $this->taxonomy_name = text(
            label: 'What should be the name for this taxonomy?',
            placeholder: 'E.g. Tags',
            required: true
        );

        $this->filename = Str::slug($this->taxonomy_name, '_');

        $options = collect(Collection::all())->pluck('title', 'handle');

        $this->collections = multisearch(
            label: "Which collection(s) do you want to attach $this->taxonomy_name to?",
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
            $this->createTaxonomy();
            $this->createBlueprint();
            $this->attachTaxonomiesToCollections();
            $this->handlePermissions();
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }

        Artisan::call('cache:clear');

        $this->info("<info>[✓]</info> Taxonomy '{$this->taxonomy_name}' created.");
    }

    /**
     * Create fieldset.
     *
     * @return bool|null
     */
    protected function createTaxonomy()
    {
        $this->checkExistence('Taxonomy', "content/globals/{$this->filename}.yaml");

        $stub = $this->getStub('/taxonomy.yaml.stub');
        $contents = Str::of($stub)
            ->replace('{{ taxonomy_name }}', $this->taxonomy_name);

        File::put(base_path("content/taxonomies/{$this->filename}.yaml"), $contents);
    }

    /**
     * Create blueprints.
     *
     * @return bool|null
     */
    protected function createBlueprint()
    {
        $this->checkExistence('Blueprint', "resources/blueprints/taxonomies/{$this->filename}/{$this->filename}.yaml");

        $stub = $this->getStub('/taxonomy_blueprint.yaml.stub');

        $contents = Str::of($stub)
            ->replace('{{ taxonomy_name }}', $this->taxonomy_name);

        if (! File::exists("resources/blueprints/taxonomies")) File::makeDirectory("resources/blueprints/taxonomies");
        File::makeDirectory("resources/blueprints/taxonomies/{$this->filename}");

        File::put(base_path("resources/blueprints/taxonomies/{$this->filename}/{$this->filename}.yaml"), $contents);
    }

    /**
     * Attach Taxonomies.
     *
     * @return bool|null
     */
    protected function attachTaxonomiesToCollections()
    {
        if (! $this->collections) {
            return;
        }

        collect($this->collections)->each(function ($item) {
            $collection = Yaml::parseFile(base_path("content/collections/{$item}.yaml"));
            $existingTaxonomies = Arr::get($collection, 'taxonomies');

            if ($existingTaxonomies) {
                $taxonomies = array_merge($existingTaxonomies, [$this->filename]);
            } else {
                $taxonomies = [$this->filename];
            }

            Arr::set($collection, 'taxonomies', $taxonomies);

            File::put(base_path("content/collections/{$item}.yaml"), Yaml::dump($collection, 99, 2));
        });
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
            "view $this->filename terms",
            "edit $this->filename terms",
            "create $this->filename terms",
            "delete $this->filename terms",
        ];

        $this->grantPermissionsToEditor($permissions);
    }
}
