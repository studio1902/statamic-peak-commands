<?php

namespace Studio1902\PeakCommands\Commands;

use Illuminate\Console\Command;
use Statamic\Console\RunsInPlease;
use Studio1902\PeakCommands\Commands\Traits\NeedsValidLicense;
use Studio1902\PeakCommands\Models\Installable;
use Studio1902\PeakCommands\Models\Partial;
use function Laravel\Prompts\info;

class MakePartial extends Command
{
    use RunsInPlease, NeedsValidLicense;

    protected $name = 'statamic:peak:make:partial';
    protected $description = "Make a partial with IDE hinting and template paths.";


    public function handle(): void
    {
        $this->checkLicense();

        $partial = app()->make(Partial::class);

        $operations = [
            [
                'type' => 'copy',
                'input' => 'partial.antlers.html.stub',
                'output' => "resources/views/$partial->folder/_$partial->filename.antlers.html",
                'replacements' => [
                    '{{ partial_name }}' => $partial->name,
                    '{{ partial_description }}' => $partial->description,
                    '{{ folder }}' => $partial->folder,
                ]
            ]
        ];

        $path = base_path('vendor/studio1902/statamic-peak-commands/resources/stubs');

        app()
            ->make(Installable::class, [
                'config' => [
                    'name' => $partial->name,
                    'handle' => $partial->filename,
                    'operations' => $operations,
                    'path' => $path,
                ]
            ])
            ->install();

        info("<info>[✓]</info> $partial->type '$partial->filename' added.");
    }
}
