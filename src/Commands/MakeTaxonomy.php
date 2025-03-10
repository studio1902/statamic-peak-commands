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
use Studio1902\PeakCommands\Models\Block;
use Studio1902\PeakCommands\Models\Installable;
use Studio1902\PeakCommands\Models\Taxonomy;
use Symfony\Component\Yaml\Yaml;
use function Laravel\Prompts\confirm;
use function Laravel\Prompts\info;
use function Laravel\Prompts\multisearch;
use function Laravel\Prompts\text;

class MakeTaxonomy extends Command
{
    use RunsInPlease, NeedsValidLicense;

    protected $name = 'statamic:peak:make:taxonomy';
    protected $description = "Make a taxonomy.";

    public function handle()
    {
        $this->checkLicense();

        $taxonomy = app()->make(Taxonomy::class);

        $operations = [
            [
                'type' => 'copy',
                'input' => 'taxonomy.yaml.stub',
                'output' => "content/taxonomies/$taxonomy->filename.yaml",
                'replacements' => [
                    '{{ taxonomy_name }}' => $taxonomy->name,
                ]
            ],
            [
                'type' => 'copy',
                'input' => 'taxonomy_blueprint.yaml.stub',
                'output' => "resources/blueprints/taxonomies/$taxonomy->filename/$taxonomy->filename.yaml",
                'replacements' => [
                    '{{ taxonomy_name }}' => $taxonomy->name,
                ]
            ],
            //TODO[mr]: add missing operations (09.03.2025 mr)
        ];
        
        $path = base_path('vendor/studio1902/statamic-peak-commands/resources/stubs');

        app()
            ->make(Installable::class, [
                'config' => [
                    'name' => $taxonomy->name,
                    'handle' => $taxonomy->filename,
                    'operations' => $operations,
                    'path' => $path,
                ]
            ])
            ->install();

        info("<info>[✓]</info> Taxonomy '$taxonomy->name' created.");
        return;
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
