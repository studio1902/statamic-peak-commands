<?php

namespace Studio1902\PeakCommands\Operations;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use ReflectionClass;
use Statamic\Support\Arr;
use Stringy\StaticStringy as Stringy;
use Studio1902\PeakCommands\Models\Block;
use Studio1902\PeakCommands\Models\Installable;
use Symfony\Component\Yaml\Yaml;
use function Laravel\Prompts\confirm;
use function Laravel\Prompts\search;
use function Laravel\Prompts\select;
use function Laravel\Prompts\text;

class UpdateArticleSets extends Operation
{
    protected Block $block;

    public function __construct(array $config)
    {
        $this->block = new Block($config['block']);
    }

    public function run(): Installable
    {
        $name = $this->block->name;
        $filename = $this->block->handle;
        $instructions = $this->block->instructions;
        $icon = $this->block->icon;

        $fieldset = Yaml::parseFile(base_path('resources/fieldsets/article.yaml'));

        $newSet = [
            'display' => $this->block->name,
            'instructions' => $instructions,
            'icon' => $icon,
            'fields' => [
                [
                    'import' => $filename
                ]
            ]
        ];

        $collection = collect(Arr::get($fieldset, 'fields'))
            ->where('handle', 'article');
        $first = $collection->first();
        $key = array_search($first, $collection->toArray());

        $existingGroups = Arr::get($fieldset, "fields.$key.field.sets");

        $useExistingGroup = confirm(
            label: 'Do you want to add this set to an existing or new group?',
            yes: 'Existing',
            no: 'New',
            default: true
        );

        if ($useExistingGroup) {
            $group = select(
                label: "In which group of article sets do you want to install: '{$name}'?",
                options: array_keys($existingGroups),
                scroll: 10
            );

            $groupSets = $existingGroups[$group];
            $existingSets = Arr::get($groupSets, 'sets');
            $existingSets[$filename] = $newSet;
            $existingSets = collect($existingSets)->sortBy(function ($value, $key) {
                return $key;
            })->all();

            Arr::set($groupSets, 'sets', $existingSets);
            $existingGroups[$group] = $groupSets;
            Arr::set($fieldset, "fields.$key.field.sets", $existingGroups);

        } else {
            $groupName = text(
                label: 'What should be the name for this group?',
                placeholder: 'Collections',
                required: true
            );

            $groupFilename = Stringy::slugify($groupName, '_');

            $groupInstructions = text(
                label: 'What should be the instructions for this group?',
                placeholder: 'Collection based content.',
                required: true
            );

            $groupIcon = $this->promptsIconPicker('Which icon do you want to use for this group?');

            $newGroup = [
                $groupFilename => [
                    'display' => $groupName,
                    'instructions' => $groupInstructions,
                    'icon' => $groupIcon,
                    'sets' => [
                        $filename => $newSet
                    ]
                ]
            ];

            $groups = array_merge($existingGroups, $newGroup);
            $orderedGroups = collect($groups)->sortBy(function ($key) {
                return $key;
            })->all();
            Arr::set($fieldset, "fields.$key.field.sets", $orderedGroups);
        }

        File::put(base_path('resources/fieldsets/article.yaml'), Yaml::dump($fieldset, 99, 2));

        info("Installed article set: '$name'.");

        return  $this->installable;
    }

    protected function updatePageBuilder($name, $instructions, $icon, $filename): void
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

        $useExistingGroup = confirm(
            label: "Do you want to add the block '$name' to an existing or new group?",
            default: true,
            yes: 'Existing',
            no: 'New'
        );

        if ($useExistingGroup) {
            $group = select(
                label: "In which group of page builder blocks do you want to install: '$name'?",
                options: array_keys($existingGroups),
                scroll: 10
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

        } else {
            $groupName = text(
                label: 'What should be the name for this group?',
                placeholder: 'Collections',
                required: true
            );

            $groupFilename = Stringy::slugify($groupName, '_');

            $groupInstructions = text(
                label: 'What should be the instructions for this group?',
                placeholder: 'Collection based content.',
                required: true
            );

            $groupIcon = $this->promptsIconPicker('Which icon do you want to use for this group?');

            $newGroup = [
                $groupFilename => [
                    'display' => $groupName,
                    'instructions' => $groupInstructions,
                    'icon' => $groupIcon,
                    'sets' => [
                        $filename => $newSet
                    ]
                ]
            ];

            $groups = array_merge($existingGroups, $newGroup);
            $orderedGroups = collect($groups)->sortBy(fn($value, $key) => $key)->all();

            Arr::set($fieldset, 'fields.0.field.sets', $orderedGroups);
        }

        File::put(base_path('resources/fieldsets/page_builder.yaml'), Yaml::dump($fieldset, 99, 2));
    }

    protected function promptsIconPicker($label)
    {
        $reflection = new ReflectionClass(\Statamic\Fieldtypes\Sets::class);
        $iconsDirectory = $reflection->getStaticPropertyValue('iconsDirectory') ?? base_path('/vendor/statamic/cms/resources/svg/icons');
        $iconsFolder = $reflection->getStaticPropertyValue('iconsFolder');

        $icons = collect(File::allFiles("$iconsDirectory/$iconsFolder"))
            ->map(fn($file) => str_replace('.svg', '', $file->getBasename('.' . $file->getExtension())));

        if (DIRECTORY_SEPARATOR === '\\') {
            return $icons->first();
        }

        return search(
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
            scroll: 10,
            required: true
        );
    }
}
