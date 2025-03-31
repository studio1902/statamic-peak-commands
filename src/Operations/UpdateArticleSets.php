<?php

namespace Studio1902\PeakCommands\Operations;

use Illuminate\Support\Facades\File;
use Statamic\Support\Arr;
use Stringy\StaticStringy as Stringy;
use Studio1902\PeakCommands\Models\Installable;
use Studio1902\PeakCommands\Models\Set;
use Studio1902\PeakCommands\Operations\Traits\CanPickIcon;
use Symfony\Component\Yaml\Yaml;
use function Laravel\Prompts\confirm;
use function Laravel\Prompts\info;
use function Laravel\Prompts\select;
use function Laravel\Prompts\text;

class UpdateArticleSets extends Operation
{
    use CanPickIcon;

    protected Set $set;

    public function __construct(array $config)
    {
        $this->set = app()->make(Set::class, ['config' => Arr::get($config, 'set')]);
    }

    public function run(): Installable
    {
        $name = $this->set->name;
        $filename = $this->set->handle;
        $instructions = $this->set->instructions;
        $icon = $this->set->icon;

        $fieldset = Yaml::parseFile(base_path('resources/fieldsets/article.yaml'));

        $newSet = [
            'display' => $this->set->name,
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
            default: true,
            yes: 'Existing',
            no: 'New'
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

            $groupIcon = $this->pickIcon('Which icon do you want to use for this group?');

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

        return $this->installable;
    }
}
