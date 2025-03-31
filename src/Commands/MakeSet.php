<?php

namespace Studio1902\PeakCommands\Commands;

use Illuminate\Console\Command;
use Statamic\Console\RunsInPlease;
use Studio1902\PeakCommands\Commands\Traits\NeedsValidLicense;
use Studio1902\PeakCommands\Models\Installable;
use Studio1902\PeakCommands\Models\Set;
use Studio1902\PeakCommands\Operations\Traits\CanPickIcon;
use function Laravel\Prompts\info;

class MakeSet extends Command
{
    use RunsInPlease, NeedsValidLicense, CanPickIcon;

    protected $name = 'statamic:peak:make:set';
    protected $description = "Make an Article (Bard) set.";

    public function handle()
    {
        $this->checkLicense();

        $set = app()->make(Set::class);


        $operations = [
            [
                'type' => 'copy',
                'input' => 'set.antlers.html.stub',
                'output' => 'resources/views/components/_{{ handle }}.antlers.html'
            ],
            [
                'type' => 'copy',
                'input' => 'fieldset_set.yaml.stub',
                'output' => 'resources/fieldsets/{{ handle }}.yaml'
            ],
            [
                'type' => 'update_article_sets',
                'set' => $set->toArray(),
            ],
        ];

        $path = base_path('vendor/studio1902/statamic-peak-commands/resources/stubs');

        app()
            ->make(Installable::class, [
                'config' => [
                    'name' => $set->name,
                    'handle' => $set->handle,
                    'operations' => $operations,
                    'path' => $path,
                ]
            ])
            ->install();

        info("<info>[✓]</info> Peak page builder Article set '$set->name' added.");
    }
}
