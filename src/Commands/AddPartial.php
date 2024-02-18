<?php

namespace Studio1902\PeakCommands\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Statamic\Console\RunsInPlease;
use Statamic\Facades\Config;
use Stringy\StaticStringy as Stringy;
use function Laravel\Prompts\select;
use function Laravel\Prompts\text;

class AddPartial extends Command
{
    use RunsInPlease, SharedFunctions, NeedsValidLicense;

    protected $name = 'statamic:peak:add-partial';
    protected $description = "Add a partial with IDE hinting and template paths.";
    protected $partial_name = '';
    protected $partial_description = '';
    protected $filename = '';
    protected $folder = '';
    protected $type = '';

    public function handle()
    {
        $this->checkLicense();

        $this->type = select(
            label: 'What type of partial do you want to add?',
            options: ['Component', 'Layout', 'Snippet', 'Typography'],
            default: 'Component'
        );

        $this->folder = strtolower($this->type);
        if ($this->folder == 'component') $this->folder = 'components';
        if ($this->folder == 'snippet') $this->folder = 'snippets';

        $this->partial_name = text(
            label: 'What should be the name for this partial?',
            placeholder: 'E.g. Card',
            required: true
        );

        $this->partial_description = text(
            label: 'What should be the description for this partial?',
            placeholder: 'E.g. A card component.',
            required: true
        );

        $this->filename = Stringy::slugify($this->partial_name, '_', Config::getShortLocale());

        try {
            $this->checkExistence('Partial', "resources/views/{$this->folder}/_{$this->filename}.antlers.html");

            $this->createPartial();
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }

        $this->info("<info>[✓]</info> {$this->type} '{$this->filename}' added.");
    }

    /**
     * Create partial.
     *
     * @return bool|null
     */
    protected function createPartial()
    {
        $stub = $this->getStub('/partial.antlers.html.stub');
        $contents = Str::of($stub)
            ->replace('{{ partial_name }}', $this->partial_name)
            ->replace('{{ partial_description }}', $this->partial_description)
            ->replace('{{ folder }}', $this->folder)
            ->replace('{{ filename }}', $this->filename);

        File::put(base_path("resources/views/{$this->folder}/_{$this->filename}.antlers.html"), $contents);
    }
}
