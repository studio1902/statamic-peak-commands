<?php

namespace Studio1902\PeakCommands\Commands;

use Illuminate\Console\Command;
use Statamic\Console\RunsInPlease;
use Studio1902\PeakCommands\Models\Block;
use Studio1902\PeakCommands\Models\Installable;
use Studio1902\PeakCommands\Operations\Traits\CanPickIcon;

class MakeBlock extends Command
{
    use RunsInPlease, NeedsValidLicense, CanPickIcon;

    protected $name = 'statamic:peak:make:block';
    protected $description = "Make a page builder block.";

    public function handle(): void
    {
        $this->checkLicense();

        $block = app()->make(Block::class);

        $operations = [
            [
                'type' => 'copy',
                'input' => 'block.antlers.html.stub',
                'output' => 'resources/views/page_builder/_{{ handle }}.antlers.html'
            ],
            [
                'type' => 'copy',
                'input' => 'fieldset_block.yaml.stub',
                'output' => 'resources/fieldsets/{{ handle }}.yaml'
            ],
            [
                'type' => 'update_page_builder',
                'block' => $block->toArray(),
            ],
        ];

        $path = base_path('vendor/studio1902/statamic-peak-commands/resources/stubs');

        app()
            ->make(Installable::class, [
                'config' => [
                    'name' => $block->name,
                    'handle' => $block->handle,
                    'operations' => $operations,
                    'path' => $path,
                ]
            ])
            ->install();

        $this->info("<info>[✓]</info> Peak page builder block '$block->name' added.");
    }
}
