<?php

namespace Studio1902\PeakCommands\Operations;

use Illuminate\Support\Facades\File;
use Statamic\Support\Arr;
use Studio1902\PeakCommands\Models\Installable;
use Symfony\Component\Yaml\Yaml;

use function Laravel\Prompts\info;

class AttachCollectionsToNavigation extends Operation
{
    protected string $navigation;

    protected array $collections;

    public function __construct(array $config)
    {
        $this->navigation = Arr::get($config, 'navigation');
        $this->collections = Arr::get($config, 'collections');
    }

    public function run(): Installable
    {
        if (! $this->collections) {
            return $this->installable;
        }

        $navigation = Yaml::parseFile(base_path("content/navigation/{$this->navigation}.yaml"));
        $existingCollections = Arr::get($navigation, 'collections', []);

        $collections = Arr::sort(array_merge($existingCollections, $this->collections));

        Arr::set($navigation, 'collections', $collections);

        File::put(base_path("content/navigation/{$this->navigation}.yaml"), Yaml::dump($navigation, 99, 2));

        info("Collections attached to '{$this->navigation}' navigation.");

        return $this->installable;
    }
}
