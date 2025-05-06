<?php

namespace Studio1902\PeakCommands\Operations;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Statamic\Support\Arr;
use Stringy\StaticStringy as Stringy;
use Studio1902\PeakCommands\Models\Block;
use Studio1902\PeakCommands\Models\Installable;
use Studio1902\PeakCommands\Operations\Traits\CanPickIcon;
use Symfony\Component\Yaml\Yaml;

use function Laravel\Prompts\confirm;
use function Laravel\Prompts\note;
use function Laravel\Prompts\select;
use function Laravel\Prompts\text;

class UpdatePageBuilder extends Operation
{
    use CanPickIcon;

    protected Block $block;

    public function __construct(array $config)
    {
        $this->block = app(Block::class, ['config' => Arr::get($config, 'block')]);
    }

    public function run(): Installable
    {
        $name = (string) Str::of($this->block->name)->replace('{{ name }}', $this->installable->renameName);

        $instructions = (string) Str::of($this->block->instructions)->replace('{{ name }}', $this->installable->renameName);

        $icon = (string) Str::of($this->block->icon);

        $handle = (string) Str::of($this->block->handle)
            ->replace('{{ handle }}', $this->installable->renameHandle);

        $this->updatePageBuilder($name, $instructions, $icon, $handle);

        note("Installed page builder block: '$name'.");

        return $this->installable;
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
                    'import' => $filename,
                ],
            ],
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

            $groupIcon = $this->pickIcon('Which icon do you want to use for this group?');

            $newGroup = [
                $groupFilename => [
                    'display' => $groupName,
                    'instructions' => $groupInstructions,
                    'icon' => $groupIcon,
                    'sets' => [
                        $filename => $newSet,
                    ],
                ],
            ];

            $groups = array_merge($existingGroups, $newGroup);
            $orderedGroups = collect($groups)->sortBy(fn ($value, $key) => $key)->all();

            Arr::set($fieldset, 'fields.0.field.sets', $orderedGroups);
        }

        File::put(base_path('resources/fieldsets/page_builder.yaml'), Yaml::dump($fieldset, 99, 2));
    }
}
