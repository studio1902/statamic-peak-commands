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
    protected string $input;
    protected string $output;
    protected bool $skippable;
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

    public function run(): Installable
    {
        $this->output = Str::of($this->output)->replace('{{ handle }}', $this->installable->renameHandle);

        Site::hasMultiple()
            ? $this->output = Str::of($this->output)->replace('{{ multisite_handle }}', Site::default()->handle)
            : $this->output = Str::of($this->output)->replace('{{ multisite_handle }}/', '');

        $stub = $this->getStub($this->input, $this->installable->path);

        $contents = Str::of($stub)
            ->replace('{{ handle }}', $this->installable->renameHandle)
            ->replace('{{ filename }}', $this->installable->renameHandle)
            ->replace('{{ name }}', $this->installable->renameName)
            ->replace('{{ singular_handle }}', $this->installable->renameSingularHandle)
            ->replace('{{ singular_name }}', $this->installable->renameSingularName);

        if ($this->skippable) {
            if ($this->checkExistenceAndSkip('File', "{$this->output}")) {
                info("Skipped file: '{$this->output}'.");
            } else {
                $this->filesystem->put($this->output, $contents);
                info("Installed file: '{$this->output}'.");
            }
        } else {
            try {
                $this->checkExistence('File', "{$this->output}");
                $this->filesystem->put($this->output, $contents);
            } catch (\Exception $e) {
                error($e->getMessage());
                exit();
            }

            info("Installed file: '{$this->output}'.");
        }

        return $this->installable;
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
