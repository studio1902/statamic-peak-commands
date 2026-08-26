<?php

namespace Studio1902\PeakCommands\Operations;

use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Studio1902\PeakCommands\Models\Installable;

use function Laravel\Prompts\confirm;

class CreateComponentCssFile extends Operation
{
    private string $filename;

    protected Filesystem $filesystem;

    public function __construct(array $config)
    {
        $this->filename = Arr::get($config, 'filename');

        $this->filesystem = Storage::build([
            'driver' => 'local',
            'root' => resource_path('css'),
        ]);
    }

    public function run(): Installable
    {
        $filePath = "/components/{$this->filename}.css";

        if ($this->fileExists($filePath) === false) {
            $this->filesystem->put($filePath, '');

            if ($this->filesystem->exists('site.css')) {
                $fileContent = $this->filesystem->get('site.css');

                $append = "@import \"./components/{$this->filename}.css\" layer(components);";
                $fileContent = str($fileContent)->replaceLast('layer(components);', 'layer(components);'.PHP_EOL.$append);
                $this->filesystem->put('site.css', $fileContent);
            }
        }

        return $this->installable;
    }

    protected function fileExists(string $path): bool
    {
        if ($this->filesystem->exists($path)) {
            return confirm(
                label: "CSS component file '{$path}' exists. Continue and overwrite?",
                default: true,
                yes: 'Keep existing file',
                no: 'Overwrite'
            );
        }

        return false;
    }
}
