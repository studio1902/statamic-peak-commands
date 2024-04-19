<?php

namespace Studio1902\PeakCommands\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Statamic\Console\RunsInPlease;
use Stringy\StaticStringy as Stringy;
use function Laravel\Prompts\multisearch;

class InstallBlock extends Command
{
    use RunsInPlease, SharedFunctions, InstallBlockBlocks, NeedsValidLicense;

    protected $name = 'statamic:peak:install-block';
    protected $description = "Install premade blocks into your page builder.";
    protected $block_name = '';
    protected $choices = '';
    protected $filename = '';
    protected $instructions = '';
    protected $icon = '';

    public function handle()
    {
        $this->checkLicense();

        $options = collect($this->getBlocks());

        $this->choices = multisearch(
            label: 'Which blocks do you want to install into your page builder?',
            options: fn (string $value) => strlen($value) > 0
                ? $options->filter(fn(string $item) => Str::contains($item, $value, true))->toArray()
                : $options->toArray(),
            scroll: 15,
            validate: fn ($values) => match (true) {
                empty($values) => 'Please select at least one block. (Space)',
                default => null,
            }
        );

        foreach($this->choices as $choice) {
            $this->block_name = Stringy::split($this->getBlocks()[$choice], ':')[0];
            $this->filename = $choice;
            $description = Stringy::split($this->getBlocks()[$choice], ': ')[1];
            $this->instructions = Stringy::split($description, ' \[')[0];
            $this->icon = rtrim(Stringy::split($description, ' \[')[1], "]");

            try {
                $this->checkExistence('Fieldset', "resources/fieldsets/{$this->filename}.yaml");
                $this->checkExistence('Partial', "resources/views/page_builder/_{$this->filename}.antlers.html");

                $this->copyStubs();
                $this->updatePageBuilder($this->block_name, $this->instructions, $this->icon, $this->filename);
            } catch (\Exception $e) {
                return $this->error($e->getMessage());
            }

            $this->info("<info>[✓]</info> Peak page builder block '{$this->block_name}' installed.");
        }
    }

    /**
     * Copy yaml and html stubs.
     *
     * @return bool|null
     */
    protected function copyStubs()
    {
        File::put(base_path("resources/fieldsets/{$this->filename}.yaml"), $this->getStub("/blocks/{$this->filename}.yaml.stub"));
        File::put(base_path("resources/views/page_builder/_{$this->filename}.antlers.html"), $this->getStub("/blocks/{$this->filename}.antlers.html.stub"));
    }
}
