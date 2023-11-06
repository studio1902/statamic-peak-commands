<?php

namespace Studio1902\PeakCommands\Commands;

use Illuminate\Support\Facades\File;
use Statamic\Support\Arr;
use Symfony\Component\Yaml\Yaml;
use function Laravel\Prompts\confirm;
use function Laravel\Prompts\select;

trait SharedFunctions {

    /**
     * Check if a file doesn't already exist.
     *
     * @return bool|null
     */
    public function checkExistence($type, $path)
    {
        if (File::exists(base_path($path))) {
            if (confirm(
                    label: "{$type} '{$path}' exists. Continue and overwrite?",
                    default: true
            )) {
                return false;
            } else {
                throw new \Exception("Aborted. {$type} '{$path}' already exists.");
            }
        }
    }

    /**
     * Update article.yaml.
     *
     * @return bool|null
     */
    protected function updateArticleSets($name, $filename)
    {
        $fieldset = Yaml::parseFile(base_path('resources/fieldsets/article.yaml'));
        $newSet = [
            'display' => $name,
            'fields' => [
                [
                    'import' => $filename
                ]
            ]
        ];

        $existingGroups = Arr::get($fieldset, 'fields.0.field.sets');
        $group = select(
            label: "In which group of article sets do you want to install: '{$name}'?",
            options: array_keys($existingGroups)
        );

        $groupSets = $existingGroups[$group];
        $existingSets = Arr::get($groupSets, 'sets');
        $existingSets[$filename] = $newSet;
        $existingSets = collect($existingSets)->sortBy(function ($value, $key) {
            return $key;
        })->all();

        Arr::set($groupSets, 'sets', $existingSets);
        $existingGroups[$group] = $groupSets;
        Arr::set($fieldset, 'fields.0.field.sets', $existingGroups);

        File::put(base_path('resources/fieldsets/article.yaml'), Yaml::dump($fieldset, 99, 2));
    }

    /**
     * Update page_builder.yaml.
     *
     * @return bool|null
     */
    protected function updatePageBuilder($name, $instructions, $icon, $filename)
    {
        $fieldset = Yaml::parseFile(base_path('resources/fieldsets/page_builder.yaml'));
        $newSet = [
            'display' => $name,
            'instructions' => $instructions,
            'icon' => $icon,
            'fields' => [
                [
                    'import' => $filename
                ]
            ]
        ];

        $existingGroups = Arr::get($fieldset, 'fields.0.field.sets');
        $group = select(
            label: "In which group of page builder blocks do you want to install: '{$name}'?",
            options: array_keys($existingGroups)
        );

        $groupSets = $existingGroups[$group];
        $existingSets = Arr::get($groupSets, 'sets');
        $existingSets[$filename] = $newSet;
        $existingSets = collect($existingSets)->sortBy(function ($value, $key) {
            return $key;
        })->all();

        Arr::set($groupSets, 'sets', $existingSets);
        $existingGroups[$group] = $groupSets;
        Arr::set($fieldset, 'fields.0.field.sets', $existingGroups);

        File::put(base_path('resources/fieldsets/page_builder.yaml'), Yaml::dump($fieldset, 99, 2));
    }


    protected function getStub(string $stubPath): string
    {
        $publishedStubPath = resource_path("stubs/vendor/statamic-peak-commands/" . ltrim($stubPath, " /\t\n\r\0\x0B"));
        $addonStubPath = __DIR__ . "/../../resources/stubs/" . ltrim($stubPath, " /\t\n\r\0\x0B");

        return File::get(File::exists($publishedStubPath) ? $publishedStubPath : $addonStubPath);
    }
}
