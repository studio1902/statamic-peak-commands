<?php

namespace Studio1902\PeakCommands\Commands;

use Illuminate\Console\Command;
use Statamic\Console\RunsInPlease;
use Statamic\Facades\Config;
use Stringy\StaticStringy as Stringy;
use Studio1902\PeakCommands\Models\Installable;
use Studio1902\PeakCommands\Operations\Traits\CanPickIcon;
use function Laravel\Prompts\text;

class MakeBlock extends Command
{
    use RunsInPlease, NeedsValidLicense, CanPickIcon;

    protected $name = 'statamic:peak:make:block';
    protected $description = "Make a page builder block.";

    public function handle(): void
    {
        $this->checkLicense();

        $name = text(
            label: 'What should be the name for this block?',
            placeholder: 'E.g. Text and image',
            required: true
        );

        $handle = text(
            label: 'What should be the filename for this block?',
            default: Stringy::slugify($name, '_', Config::getShortLocale()),
            required: true
        );

        $description = text(
            label: 'What should be the instructions for this block?',
            placeholder: 'E.g. Renders text and an image.',
            required: true
        );

        $path = base_path('vendor/studio1902/statamic-peak-commands/resources/stubs');

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
                'block' => [
                    'name' => $name,
                    'instructions' => $description,
                    'icon' => $this->pickIcon('Which icon do you want to use for this block?'),
                    'handle' => $handle,
                ]
            ],
        ];

        app()->make(Installable::class, ['config' => compact('name', 'handle', 'description', 'operations', 'path')])->install();

        $this->info("<info>[✓]</info> Peak page builder block '$name' added.");
    }
}
