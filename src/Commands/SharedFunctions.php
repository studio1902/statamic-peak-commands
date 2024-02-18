<?php

namespace Studio1902\PeakCommands\Commands;

use Facades\Statamic\Licensing\LicenseManager;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Statamic\Support\Arr;
use Symfony\Component\Yaml\Yaml;
use function Laravel\Prompts\confirm;
use function Laravel\Prompts\search;
use function Laravel\Prompts\select;

trait SharedFunctions {

    protected static $licensed = false;

    public function __construct()
    {
        $addonLicense = LicenseManager::addons()->first(function ($addonLicense) {
            return $addonLicense->addon()->id() === 'studio1902/statamic-peak-commands';
        });

        static::$licensed = $addonLicense && $addonLicense->valid();
    }

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
     * Prompt a search dialogue requesting an icon.
     *
     * @return string|null
     */
    protected function promptsIconPicker($label)
    {
        $reflection = new \ReflectionClass(\Statamic\Fieldtypes\Sets::class);
        $iconsDirectory = $reflection->getStaticPropertyValue('iconsDirectory') ?? base_path('/vendor/statamic/cms/resources/svg/icons');
        $iconsFolder = $reflection->getStaticPropertyValue('iconsFolder');

        $icons = collect(File::allFiles("$iconsDirectory/$iconsFolder"))->map(function ($file) {
            return str_replace('.svg', '', $file->getBasename('.'.$file->getExtension()));
        });

        if (DIRECTORY_SEPARATOR === '\\') {
            return $icons->first();
        }

        return $this->icon = search(
            label: $label,
            options: function (string $value) use ($icons) {
                if (!$value) {
                    return $icons
                        ->values()
                        ->all();
                }

                return $icons
                    ->filter(fn(string $item) => Str::contains($item, $value, true))
                    ->values()
                    ->all();
            },
            placeholder: 'file-content-list',
            required: true
        );
    }

    /**
     * Update article.yaml.
     *
     * @return bool|null
     */
    protected function updateArticleSets($name, $filename, $instructions, $icon)
    {
        $fieldset = Yaml::parseFile(base_path('resources/fieldsets/article.yaml'));
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
