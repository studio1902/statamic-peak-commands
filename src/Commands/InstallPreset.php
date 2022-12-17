<?php

namespace Studio1902\Peak\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Statamic\Console\RunsInPlease;
use Statamic\Support\Arr;
use Stringy\StaticStringy as Stringy;
use Symfony\Component\Yaml\Yaml;

class InstallPreset extends Command
{
    use RunsInPlease, SharedFunctions, InstallPresetPresets;

    protected $rename = false;
    protected $rename_handle = '';
    protected $rename_name = '';
    protected $choices = '';
    protected $description = "Install premade collections and page builder blocks into your site.";
    protected $handle = '';
    protected $name = 'statamic:peak:install-preset';

    public function handle()
    {
        $this->getPresets();

        $this->choices = $this->choice(
            'Which presets do you want to install into your site? You can separate multiple answers with a comma',
            $this->presets->map(function ($preset, $key) {
                return "{$preset['name']}: {$preset['description']} [{$preset['handle']}]";
            })->toArray(),
            null, null, true
        );

        $target = Storage::build([
            'driver' => 'local',
            'root' => base_path(),
        ]);

        foreach($this->choices as $choice) {
            $this->handle = Stringy::between($choice, '[', ']');
            $preset = $this->presets->filter(function ($preset, $key) {
                return $preset['handle'] == $this->handle;
            })->first();

            collect($preset['operations'])->each(function ($operation, $key) use ($target) {
                if ($operation['type'] == 'copy') {
                    $this->rename
                        ? $output = Str::of($operation['output'])->replace('{{ handle }}',$this->rename_handle)
                        : $output = $operation['output'];

                    $stub = File::get(__DIR__."/stubs/presets/{$this->handle}/{$operation['input']}");
                    $contents = Str::of($stub)
                        ->replace('{{ handle }}', $this->rename_handle)
                        ->replace('{{ name }}', $this->rename_name);

                    $target->put($output, $contents);
                    $this->info("Installed file: '{$output}'.");
                }

                elseif ($operation['type'] == 'rename') {
                    $this->rename = true;
                    $this->rename_name = $this->ask('What should be the collection name?');
                    $this->rename_handle = Str::slug($this->rename_name, '_');
                }

                elseif ($operation['type'] == 'update_article_sets') {
                    $this->updateArticleSets($operation['block']['name'], $operation['block']['handle']);
                    $this->info("Installed article set: '{$operation['block']['name']}'.");
                }

                elseif ($operation['type'] == 'update_page_builder') {
                    $name = (string)Str::of($operation['block']['name'])
                        ->replace('{{ name }}', $this->rename_name);
                    $instructions = (string)Str::of($operation['block']['instructions'])
                        ->replace('{{ name }}', $this->rename_name);
                    $handle = (string)Str::of($operation['block']['handle'])
                        ->replace('{{ handle }}', $this->rename_handle);

                    $this->updatePageBuilder($name, $instructions, $handle);
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
                }
            });

            Artisan::call('cache:clear');

            $this->info("<info>[✓]</info> Peak preset '{$preset['name']}' installed.");
        }
    }
}
