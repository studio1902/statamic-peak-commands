<?php

namespace Studio1902\PeakCommands\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Statamic\Console\RunsInPlease;
use Stringy\StaticStringy as Stringy;
use function Laravel\Prompts\multisearch;

class InstallSet extends Command
{
    use RunsInPlease, SharedFunctions, InstallSetSets, NeedsValidLicense;

    protected $name = 'statamic:peak:install:set';
    protected $description = "Install premade sets into your article field.";
    protected $set_name = '';
    protected $choices = '';
    protected $filename = '';
    protected $instructions = '';
    protected $icon = '';

    public function handle()
    {
        $this->checkLicense();

        $options = collect($this->getSets());

        $this->choices = multisearch(
            label: 'Which sets do you want to install into your article field?',
            options: fn (string $value) => strlen($value) > 0
                ? $options->filter(fn(string $item) => Str::contains($item, $value, true))->toArray()
                : $options->toArray(),
            scroll: 15,
            validate: fn ($values) => match (true) {
                empty($values) => 'Please select at least one set. (Space)',
                default => null,
            }
        );

        foreach($this->choices as $choice) {
            $this->set_name = Stringy::split($this->getSets()[$choice], ':')[0];
            $this->filename = $choice;
            $description = Stringy::split($this->getSets()[$choice], ': ')[1];
            $this->instructions = Stringy::split($description, ' \[')[0];
            $this->icon = rtrim(Stringy::split($description, ' \[')[1], "]");

            try {
                $this->checkExistence('Fieldset', "resources/fieldsets/{$this->filename}.yaml");
                $this->checkExistence('Partial', "resources/views/components/_{$this->filename}.antlers.html");

                $this->copyStubs();
                $this->updateArticleSets($this->set_name, $this->filename, $this->instructions, $this->icon);
            } catch (\Exception $e) {
                return $this->error($e->getMessage());
            }

            $this->info("<info>[✓]</info> Peak Article Set '{$this->set_name}' installed.");
        }
    }

    /**
     * Copy yaml and html stubs.
     *
     * @return bool|null
     */
    protected function copyStubs()
    {
        File::put(base_path("resources/fieldsets/{$this->filename}.yaml"), $this->getStub("/sets/{$this->filename}.yaml.stub"));
        File::put(base_path("resources/views/page_builder/_{$this->filename}.antlers.html"), $this->getStub("/sets/{$this->filename}.antlers.html.stub"));
    }
}
