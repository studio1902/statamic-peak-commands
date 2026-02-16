<?php

namespace Studio1902\PeakCommands\Operations;

use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Stringable;
use Statamic\Facades\Site;
use Statamic\Support\Arr;
use Studio1902\PeakCommands\Models\Installable;

use function Laravel\Prompts\confirm;
use function Laravel\Prompts\error;
use function Laravel\Prompts\note;

class Copy extends Operation
{
    protected string $input;

    protected string $output;

    protected bool $skippable;

    protected bool $skip_default_replacements;

    protected array $replacements;

    protected Filesystem $filesystem;

    public function __construct(array $config)
    {
        $this->input = Arr::get($config, 'input');
        $this->output = Arr::get($config, 'output');
        $this->skippable = Arr::get($config, 'skippable', false);
        $this->skip_default_replacements = Arr::get($config, 'skip_default_replacements', false);
        $this->replacements = Arr::get($config, 'replacements', []);


        $this->filesystem = Storage::build([
            'driver' => 'local',
            'root' => base_path(),
        ]);
    }

    public function run(): Installable
    {
        $this->output = Str::of($this->output)->replace('{{ handle }}', $this->installable->renameHandle);
        $this->output = Str::of($this->output)->replace('{{ filepath }}', $this->installable->filepath);

        Site::hasMultiple()
            ? $this->output = Str::of($this->output)->replace('{{ multisite_handle }}', Site::default()->handle)
            : $this->output = Str::of($this->output)->replace('{{ multisite_handle }}/', '');

        $stub = $this->getStub($this->input, $this->installable->basePath);

        $contents = $this->mergedReplacements()
            ->reduce(
                fn (Stringable $contents, $replacement, $placeholder) => $contents->replace($placeholder, $replacement),
                Str::of($stub)
            );

        if ($this->skippable) {
            if ($this->checkExistenceAndSkip('File', "{$this->output}")) {
                note("Skipped file: '{$this->output}'.");

                return $this->installable;
            }

            $this->filesystem->put($this->output, $contents);
            note("Installed file: '{$this->output}'.");

            return $this->installable;
        }

        try {
            $this->checkExistence('File', "{$this->output}");
            $this->filesystem->put($this->output, $contents);
        } catch (\Exception $e) {
            error($e->getMessage());
            exit();
        }

        note("Installed file: '{$this->output}'.");

        return $this->installable;
    }

    protected function getStub(string $stubPath, string $basePath): string
    {
        $basePath = Str::endsWith($basePath, 'config.php') ? dirname($basePath) : $basePath;

        $stubPath = ltrim($stubPath, " /\t\n\r\0\x0B");
        $addonPath = $basePath . DIRECTORY_SEPARATOR . $this->normalizePath($stubPath);

        $stubPath = Str::of($stubPath)->replaceStart('stubs/', '')->toString();
        $publishedPath = resource_path('stubs/vendor/statamic-peak-commands/'.$stubPath);

        return File::get(File::exists($publishedPath) ? $publishedPath : $addonPath);
    }

    protected function normalizePath(string $path): string
    {
        return str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $path);
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

    protected function mergedReplacements(): Collection
    {
        if($this->skip_default_replacements) {
            return collect($this->replacements);
        }

        $defaultReplacements = [
            '{{ handle }}' => $this->installable->renameHandle,
            '{{ filename }}' => $this->installable->renameHandle,
            '{{ name }}' => $this->installable->renameName,
            '{{ singular_handle }}' => $this->installable->renameSingularHandle,
            '{{ singular_name }}' => $this->installable->renameSingularName,
            '{{ filepath }}' => $this->installable->filepath,
            '{{ filepath_dotted }}' => Str::of($this->installable->filepath)->replace('/', '.'),
        ];

        return collect(array_merge($this->replacements, $defaultReplacements));
    }
}
