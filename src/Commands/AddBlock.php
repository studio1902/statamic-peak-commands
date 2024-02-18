<?php

namespace Studio1902\PeakCommands\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Statamic\Console\RunsInPlease;
use Statamic\Facades\Config;
use Stringy\StaticStringy as Stringy;
use function Laravel\Prompts\text;

class AddBlock extends Command
{
    use RunsInPlease, SharedFunctions, NeedsValidLicense;

    protected $name = 'statamic:peak:add-block';
    protected $description = "Add a page builder block.";
    protected $block_name = '';
    protected $filename = '';
    protected $instructions = '';
    protected $icon = '';

    public function handle()
    {
        $this->checkLicense();

        $this->block_name = text(
            label: 'What should be the name for this block?',
            placeholder: 'E.g. Text and image',
            required: true
        );

        $this->filename = text(
            label: 'What should be the filename for this block?',
            default: Stringy::slugify($this->block_name, '_', Config::getShortLocale()),
            required: true
        );

        $this->instructions = text(
            label: 'What should be the instructions for this block?',
            placeholder: 'E.g. Renders text and an image.',
            required: true
        );

        $this->icon = $this->promptsIconPicker('Which icon do you want to use for this block?');

        try {
            $this->checkExistence('Fieldset', "resources/fieldsets/{$this->filename}.yaml");
            $this->checkExistence('Partial', "resources/views/page_builder/_{$this->filename}.antlers.html");

            $this->createFieldset();
            $this->createPartial();
            $this->updatePageBuilder($this->block_name, $this->instructions, $this->icon, $this->filename);
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }

        $this->info("<info>[✓]</info> Peak page builder block '{$this->block_name}' added.");
    }

    /**
     * Create fieldset.
     *
     * @return bool|null
     */
    protected function createFieldset()
    {
        $stub = $this->getStub('/fieldset_block.yaml.stub');
        $contents = Str::of($stub)
            ->replace('{{ name }}', str_replace('"','\'', $this->block_name));

        File::put(base_path("resources/fieldsets/{$this->filename}.yaml"), $contents);
    }

    /**
     * Create partial.
     *
     * @return bool|null
     */
    protected function createPartial()
    {
        $stub = $this->getStub('/block.antlers.html.stub');
        $contents = Str::of($stub)
            ->replace('{{ name }}', $this->block_name)
            ->replace('{{ filename }}', $this->filename);

        File::put(base_path("resources/views/page_builder/_{$this->filename}.antlers.html"), $contents);
    }
}
