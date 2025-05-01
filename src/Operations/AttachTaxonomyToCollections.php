<?php

namespace Studio1902\PeakCommands\Operations;

use Illuminate\Support\Facades\File;
use Statamic\Support\Arr;
use Studio1902\PeakCommands\Models\Installable;
use Symfony\Component\Yaml\Yaml;

use function Laravel\Prompts\info;

class AttachTaxonomyToCollections extends Operation
{
    protected string $taxonomy;

    protected array $collections;

    public function __construct(array $config)
    {
        $this->taxonomy = Arr::get($config, 'taxonomy');
        $this->collections = Arr::get($config, 'collections');
    }

    public function run(): Installable
    {
        if (! $this->collections) {
            return $this->installable;
        }

        collect($this->collections)->each(function ($collectionHandle) {
            $collection = Yaml::parseFile(base_path("content/collections/{$collectionHandle}.yaml"));
            $existingTaxonomies = Arr::get($collection, 'taxonomies', []);

            $taxonomies = Arr::sort(array_merge($existingTaxonomies, [$this->taxonomy]));

            Arr::set($collection, 'taxonomies', array_values($taxonomies));

            File::put(base_path("content/collections/{$collectionHandle}.yaml"), Yaml::dump($collection, 99, 2));

            info("Taxonomy '{$this->taxonomy}' attached to '{$collectionHandle}' collection.");
        });

        return $this->installable;
    }
}
