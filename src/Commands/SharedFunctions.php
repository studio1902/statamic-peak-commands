<?php

namespace Studio1902\PeakCommands\Commands;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Statamic\Support\Arr;
use Stringy\StaticStringy as Stringy;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;
use Symfony\Component\Yaml\Yaml;
use function Laravel\Prompts\confirm;
use function Laravel\Prompts\multisearch;
use function Laravel\Prompts\search;
use function Laravel\Prompts\select;
use function Laravel\Prompts\spin;
use function Laravel\Prompts\text;

trait SharedFunctions
{

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
     * Grant permissions to editor.
     *
     * @return bool|null
     */
    protected function grantPermissionsToEditor($newPermissions)
    {
        $roles = Yaml::parseFile(base_path('resources/users/roles.yaml'));

        $existingPermissions = Arr::get($roles, 'editor.permissions');
        $permissions = array_merge($existingPermissions, $newPermissions);

        Arr::set($roles, 'editor.permissions', $permissions);

        File::put(base_path('resources/users/roles.yaml'), Yaml::dump($roles, 99, 2));
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
            return str_replace('.svg', '', $file->getBasename('.' . $file->getExtension()));
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
            label: "Do you want to add the block '{$name}' to an existing or new group?",
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


    protected function getStub(string $stubPath, string $basePath): string
    {
        $publishedPath = resource_path("stubs/vendor/statamic-peak-commands/" . ltrim($stubPath, " /\t\n\r\0\x0B"));
        $addonPath = $basePath . DIRECTORY_SEPARATOR . ltrim($stubPath, " /\t\n\r\0\x0B");

        return File::get(File::exists($publishedPath) ? $publishedPath : $addonPath);
    }

    protected function collectOptions(): Collection
    {
        return $this->items->mapWithKeys(fn(array $item) => [$item['handle'] => "{$item['name']}: {$item['description']}"]);
    }

    protected function collectChoices(string $label, string $emptyValidation): void
    {
        $options = $this->collectOptions();

        $this->choices = multisearch(
            label: $label,
            options: fn(string $value) => strlen($value) > 0
                ? $options->filter(fn(string $item) => Str::contains($item, $value, true))->toArray()
                : $options->toArray(),
            scroll: 15,
            validate: fn($values) => match (true) {
                empty($values) => $emptyValidation,
                default => null,
            }
        );
    }

    protected function loadItems(string $type): void
    {
        $this->items = collect(config('statamic-peak-commands.paths.' . $type))
            ->map(fn($path) => \Statamic\Support\Str::ensureRight($path, DIRECTORY_SEPARATOR))
            ->flatMap(fn(string $path) => File::glob($path . '*/config.php'))
            ->unique()
            ->map(fn(string $path) => collect(['path' => \Statamic\Support\Str::removeRight($path, DIRECTORY_SEPARATOR . 'config.php')])
                ->merge(include $path)
                ->sort()
                ->all()
            )
            ->mapWithKeys(fn(array $preset) => [$preset['handle'] => $preset]);
    }
}
