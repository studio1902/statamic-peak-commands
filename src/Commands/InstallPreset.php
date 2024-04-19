<?php

namespace Studio1902\PeakCommands\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Statamic\Console\RunsInPlease;
use Statamic\Facades\Site;
use Statamic\Support\Arr;
use Symfony\Component\Yaml\Yaml;
use function Laravel\Prompts\multisearch;
use function Laravel\Prompts\pause;
use function Laravel\Prompts\text;

class InstallPreset extends Command
{
    use RunsInPlease, SharedFunctions, InstallPresetPresets, NeedsValidLicense;

    protected $rename = false;
    protected $rename_handle = '';
    protected $rename_name = '';
    protected $rename_singular_name = '';
    protected $rename_singular_handle = '';
    protected $choices = '';
    protected $description = "Install premade collections and page builder blocks into your site.";
    protected $handle = '';
    protected $name = 'statamic:peak:install-preset';

    public function handle()
    {
        $this->checkLicense();

        $this->getPresets();

        $options = collect($this->presets->mapWithKeys(function ($preset, $key) {
            return [$preset['handle'] => "{$preset['name']}: {$preset['description']}"];
        }));

        $this->choices = multisearch(
            label: 'Which presets do you want to install into your site?',
            options: fn (string $value) => strlen($value) > 0
                ? $options->filter(fn(string $item) => Str::contains($item, $value, true))->toArray()
                : $options->toArray(),
            scroll: 15,
            validate: fn ($values) => match (true) {
                empty($values) => 'Please select at least one preset. (Space)',
                default => null,
            }
        );

        $target = Storage::build([
            'driver' => 'local',
            'root' => base_path(),
        ]);

        foreach($this->choices as $key => $choice) {
            $this->handle = $choice;
            $preset = $this->presets->filter(function ($preset, $key) {
                return $preset['handle'] == $this->handle;
            })->first();

            collect($preset['operations'])->each(function ($operation, $key) use ($target, $preset) {
                if ($operation['type'] == 'copy') {
                    $this->rename
                        ? $output = Str::of($operation['output'])->replace('{{ handle }}',$this->rename_handle)
                        : $output = $operation['output'];

                    $multisite = Site::hasMultiple();
                    $handle = Site::default()->handle;

                    $multisite
                        ? $output = Str::of($output)->replace('{{ multisite_handle }}', $handle)
                        : $output = Str::of($output)->replace('{{ multisite_handle }}/', '');

                    $stub = $this->getStub("/presets/{$this->handle}/{$operation['input']}");
                    $contents = Str::of($stub)
                        ->replace('{{ handle }}', $this->rename_handle)
                        ->replace('{{ name }}', $this->rename_name)
                        ->replace('{{ singular_handle }}', $this->rename_singular_handle)
                        ->replace('{{ singular_name }}', $this->rename_singular_name);

                    if (isset($operation['skippable']) && $operation['skippable']) {
                        if ($this->checkExistenceAndSkip('File', "{$output}")) {
                            $this->info("Skipped file: '{$output}'.");
                        } else {
                            $target->put($output, $contents);
                            $this->info("Installed file: '{$output}'.");
                        }
                    } else {
                        try {
                            $this->checkExistence('File', "{$output}");
                            $target->put($output, $contents);
                        } catch (\Exception $e) {
                            exit($this->error($e->getMessage()));
                        }

                        $this->info("Installed file: '{$output}'.");
                    }
                }

                elseif ($operation['type'] == 'rename') {
                    $this->rename = true;
                    $this->rename_name = text(
                        label: "What should be the collection name for '{$preset['name']}'?",
                        placeholder: "E.g. Events",
                        required: true
                    );
                    $this->rename_handle = Str::slug($this->rename_name, '_');
                    $this->rename_singular_name = ucfirst(text(
                        label: "What is the singular name for this '{$this->rename_name}' collection?",
                        placeholder: "E.g. Event",
                        required: true
                    ));
                    $this->rename_singular_handle = Str::slug($this->rename_singular_name, '_');
                }

                elseif ($operation['type'] == 'run') {
                    $this->runCustomCommand($operation['command'], $operation['processing_message'], $operation['success_message']);
                }

                elseif ($operation['type'] == 'update_article_sets') {
                    $this->updateArticleSets($operation['block']['name'], $operation['block']['handle'], $operation['block']['description'], $operation['block']['icon']);
                    $this->info("Installed article set: '{$operation['block']['name']}'.");
                }

                elseif ($operation['type'] == 'update_page_builder') {
                    $name = (string)Str::of($operation['block']['name'])
                        ->replace('{{ name }}', $this->rename_name);
                    $instructions = (string)Str::of($operation['block']['instructions'])
                        ->replace('{{ name }}', $this->rename_name);
                    $icon = (string)Str::of($operation['block']['icon']);
                    $handle = (string)Str::of($operation['block']['handle'])
                        ->replace('{{ handle }}', $this->rename_handle);

                    $this->updatePageBuilder($name, $instructions, $icon, $handle);
                    $this->info("Installed page builder block: '{$name}'.");
                }

                elseif ($operation['type'] == 'update_role') {
                    $roles = Yaml::parseFile(base_path('resources/users/roles.yaml'));
                    $existingPermissions = Arr::get($roles, "{$operation['role']}.permissions");
                    $permissions = array_merge($existingPermissions, str_replace('{{ handle }}', $this->rename_handle, $operation['permissions']));

                    Arr::set($roles, 'editor.permissions', $permissions);

                    File::put(base_path('resources/users/roles.yaml'), Yaml::dump($roles, 99, 2));
                }

                elseif($operation['type'] == 'notify') {
                    $message = (string)Str::of($operation['content'])
                        ->replace('{{ handle }}', $this->rename_handle)
                        ->replace('{{ name }}', $this->rename_name);

                    $this->newLine();
                    $this->warn($message);
                    $this->newLine();

                    pause('Follow the instructions and press ENTER to continue.');
                }
            });

            $this->info("<info>[✓]</info> Peak preset '{$preset['name']}' installed.");

            if ($key === array_key_last($this->choices)) {
                Artisan::call('cache:clear');
            }
        }
    }
}
