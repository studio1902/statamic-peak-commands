<?php

namespace Studio1902\PeakCommands\Operations;

use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Statamic\Facades\Site;
use Studio1902\PeakCommands\Models\Installable;
use function Laravel\Prompts\confirm;
use function Laravel\Prompts\error;
use function Laravel\Prompts\info as info;

class Copy extends Operation
{
    protected string $input = '';
    protected string $output = '';
    protected bool $skippable = false;
    protected Filesystem $filesystem;

    public function __construct(array $config)
    {
        $this->input = $config['input'];
        $this->output = $config['output'];
        $this->skippable = $config['skippable'] ?? false;

        $this->filesystem = Storage::build([
            'driver' => 'local',
            'root' => base_path(),
        ]);
    }

    public function run(Installable $installable): Installable
    {
        $installable->rename
            ? $output = Str::of($this->output)->replace('{{ handle }}', $installable->renameHandle)
            : $output = $this->output;

        Site::hasMultiple()
            ? $output = Str::of($output)->replace('{{ multisite_handle }}', Site::default()->handle)
            : $output = Str::of($output)->replace('{{ multisite_handle }}/', '');

        $stub = $this->getStub($this->input, $installable->path);

        $contents = Str::of($stub)
            ->replace('{{ handle }}', $installable->renameHandle)
            ->replace('{{ name }}', $installable->renameName)
            ->replace('{{ singular_handle }}', $installable->renameSingularHandle)
            ->replace('{{ singular_name }}', $installable->renameSingularName);

        if ($this->skippable) {
            if ($this->checkExistenceAndSkip('File', "{$output}")) {
                info("Skipped file: '{$output}'.");
            } else {
                $this->filesystem->put($output, $contents);
                info("Installed file: '{$output}'.");
            }
        } else {
            try {
                $this->checkExistence('File', "{$output}");
                $this->filesystem->put($output, $contents);
            } catch (\Exception $e) {
                error($e->getMessage());
                exit();
            }

            info("Installed file: '{$output}'.");
        }

        return $installable;
    }

    protected function getStub(string $stubPath, string $basePath): string
    {
        $publishedPath = resource_path("stubs/vendor/statamic-peak-commands/" . ltrim($stubPath, " /\t\n\r\0\x0B"));
        $addonPath = $basePath . DIRECTORY_SEPARATOR . ltrim($stubPath, " /\t\n\r\0\x0B");

        return File::get(File::exists($publishedPath) ? $publishedPath : $addonPath);
    }

    protected function checkExistenceAndSkip($type, $path): bool
    {
        if (File::exists(base_path($path))) {
            return confirm(
                label: "{$type} '{$path}' exists. Skip or overwrite?",
                default: true,
                yes: 'Skip',
                no: 'Overwrite'
            );
        }
        return false;
    }

    protected function checkExistence($type, $path): bool
    {
        if (File::exists(base_path($path))) {
            if (confirm(
                label: "{$type} '{$path}' exists. Continue and overwrite?",
                default: true,
                yes: 'Overwrite',
                no: 'Abort'
            )) {
                return false;
            } else {
                throw new \Exception("Aborted. {$type} '{$path}' already exists.");
            }
        }
        return false;
    }
}
