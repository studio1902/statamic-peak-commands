<?php

namespace Studio1902\PeakCommands\Commands;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Statamic\Support\Arr;
use Stringy\StaticStringy as Stringy;
use Symfony\Component\Yaml\Yaml;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;
use function Laravel\Prompts\confirm;
use function Laravel\Prompts\search;
use function Laravel\Prompts\select;
use function Laravel\Prompts\spin;
use function Laravel\Prompts\text;

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
                    yes: 'Overwrite',
                    no: 'Abort',
                    default: true
            )) {
                return false;
            } else {
                throw new \Exception("Aborted. {$type} '{$path}' already exists.");
            }
        }
    }

    /**
     * Check if a file doesn't already exist.
     *
     * @return bool|null
     */
    public function checkExistenceAndSkip($type, $path)
    {
        if (File::exists(base_path($path))) {
            return confirm(
                label: "{$type} '{$path}' exists. Skip or overwrite?",
                yes: 'Skip',
                no: 'Overwrite',
                default: true
            );
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
            required: true,
            scroll: 10
        );
    }

    /**
     * Run command.
     *
     * @return bool|null
     */
    protected function runCustomCommand(string $command, string $processingMessage = '', string $successMessage = '', ?string $errorMessage = null, bool $tty = false, bool $spinner = true, int $timeout = 120): bool
    {
        $process = new Process(explode(' ', $command));
        $process->setTimeout($timeout);

        if ($tty) {
            $process->setTty(true);
        }

        try {
            $spinner ?
                $this->withSpinner(
                    fn() => $process->mustRun(),
                    $processingMessage,
                    $successMessage
                ) :
                $this->withoutSpinner(
                    fn() => $process->mustRun(),
                    $successMessage
                );

            return true;
        } catch (ProcessFailedException $exception) {
            error($errorMessage ?? $exception->getMessage());

            return false;
        }
    }

    protected function withSpinner(callable $callback, string $processingMessage = '', string $successMessage = ''): void
    {
        spin($callback, $processingMessage);

        if ($successMessage) {
            info("[✓] $successMessage");
        }
    }

    protected function withoutSpinner(callable $callback, string $successMessage = ''): void
    {
        $callback();

        if ($successMessage) {
            info("[✓] $successMessage");
        }
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

        $useExistingGroup = confirm(
            label: 'Do you want to add this block to an existing or new group?',
            yes: 'Existing',
            no: 'New',
            default: true
        );

        if ($useExistingGroup) {
            $group = select(
                label: "In which group of page builder blocks do you want to install: '{$name}'?",
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
            $orderedGroups = collect($groups)->sortBy(function ($value, $key) {
                return $key;
            })->all();

            Arr::set($fieldset, 'fields.0.field.sets', $orderedGroups);
        }

        File::put(base_path('resources/fieldsets/page_builder.yaml'), Yaml::dump($fieldset, 99, 2));
    }


    protected function getStub(string $stubPath): string
    {
        $publishedStubPath = resource_path("stubs/vendor/statamic-peak-commands/" . ltrim($stubPath, " /\t\n\r\0\x0B"));
        $addonStubPath = __DIR__ . "/../../resources/stubs/" . ltrim($stubPath, " /\t\n\r\0\x0B");

        return File::get(File::exists($publishedStubPath) ? $publishedStubPath : $addonStubPath);
    }
}
