<?php
//TODO[mr]: delete file in the end (05.02.2025 mr)
namespace Studio1902\PeakCommands\Commands\Traits;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Statamic\Facades\Site;
use Statamic\Support\Arr;
use Symfony\Component\Yaml\Yaml;
use function Laravel\Prompts\pause;
use function Laravel\Prompts\text;

trait Operations
{
    protected function operationRename(array $operation, array $preset): void
    {
        $this->rename = true;
        $this->rename_name = text(
            label: "What should be the collection name for '{$preset['name']}'?",
            placeholder: "E.g. '{$preset['name']}'",
            required: true
        );

        $this->rename_handle = Str::slug($this->rename_name, '_');

        $this->rename_singular_name = ucfirst(text(
            label: "What is the singular name for this '{$this->rename_name}' collection?",
            placeholder: "E.g. '{$preset['singular_name']}'",
            required: true
        ));

        $this->rename_singular_handle = Str::slug($this->rename_singular_name, '_');
    }

    protected function operationCopy(array $operation, array $preset): void
    {
        $path = $preset['path'];

        $target = Storage::build([
            'driver' => 'local',
            'root' => base_path(),
        ]);

        $this->rename
            ? $output = Str::of($operation['output'])->replace('{{ handle }}', $this->rename_handle)
            : $output = $operation['output'];

        $multisite = Site::hasMultiple();
        $handle = Site::default()->handle;

        $multisite
            ? $output = Str::of($output)->replace('{{ multisite_handle }}', $handle)
            : $output = Str::of($output)->replace('{{ multisite_handle }}/', '');

        $stub = $this->getStub($operation['input'], $path);
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

    protected function operationUpdateArticleSets(array $operation, array $preset): void
    {
        $block = $operation['block'];
        $this->updateArticleSets($block['name'], $block['handle'], $block['instructions'], $block['icon']);
        $this->info("Installed article set: '{$block['name']}'.");
    }

    protected function operationUpdatePageBuilder(array $operation, array $preset): void
    {
        $block = $operation['block'];
        $name = (string)Str::of($block['name'])
            ->replace('{{ name }}', $this->rename_name);
        $instructions = (string)Str::of($block['instructions'])
            ->replace('{{ name }}', $this->rename_name);
        $icon = (string)Str::of($block['icon']);
        $handle = (string)Str::of($block['handle'])
            ->replace('{{ handle }}', $this->rename_handle);

        $this->updatePageBuilder($name, $instructions, $icon, $handle);
        $this->info("Installed page builder block: '{$name}'.");
    }

    protected function operationUpdateRole(array $operation, array $preset): void
    {
        $roles = Yaml::parseFile(base_path('resources/users/roles.yaml'));

        $existingPermissions = Arr::get($roles, "{$operation['role']}.permissions");
        $permissions = array_merge($existingPermissions, str_replace('{{ handle }}', $this->rename_handle, $operation['permissions']));

        Arr::set($roles, 'editor.permissions', $permissions);

        File::put(base_path('resources/users/roles.yaml'), Yaml::dump($roles, 99, 2));
    }

    protected function operationNotify(array $operation, array $preset): void
    {
        $content = $operation['content'];

        $message = (string)Str::of($content)
            ->replace('{{ handle }}', $this->rename_handle)
            ->replace('{{ name }}', $this->rename_name);

        $this->newLine();
        $this->warn($message);
        $this->newLine();

        pause('Follow the instructions and press ENTER to continue.');
    }

    protected function operationRun(array $operation, array $preset): void
    {
        $this->runCustomCommand($operation['command'], $operation['processing_message'], $operation['success_message']);
    }
}
