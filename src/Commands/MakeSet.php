<?php

namespace Studio1902\PeakCommands\Commands;

use Illuminate\Console\Command;
use Statamic\Console\RunsInPlease;
use Statamic\Facades\Config;
use Stringy\StaticStringy as Stringy;
use Studio1902\PeakCommands\Models\Installable;
use Studio1902\PeakCommands\Operations\Traits\CanPickIcon;
use function Laravel\Prompts\text;

class MakeSet extends Command
{
    use RunsInPlease, NeedsValidLicense, CanPickIcon;

    protected $name = 'statamic:peak:make:set';
    protected $description = "Make an Article (Bard) set.";

    public function handle()
    {
        $this->checkLicense();

        $name = text(
            label: 'What should be the name for this set?',
            placeholder: 'E.g. Card',
            required: true
        );

        $handle = Stringy::slugify($name, '_', Config::getShortLocale());

        $description = text(
            label: 'What should be the instructions for this set?',
            placeholder: 'E.g. Lead text that renders big and bold.',
            required: true
        );

        $path = base_path('vendor/studio1902/statamic-peak-commands/resources/stubs');

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
                'block' => [
                    'name' => $name,
                    'instructions' => $description,
                    'icon' => $this->pickIcon('Which icon do you want to use for this set?'),
                    'handle' => $handle,
                ]
            ],
        ];

        app()->make(Installable::class, ['config' => compact('name', 'handle', 'description', 'operations', 'path')])->install();

        $this->info("<info>[✓]</info> Peak page builder Article set '$name' added.");
    }

}
