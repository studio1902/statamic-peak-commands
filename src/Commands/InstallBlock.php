<?php

namespace Studio1902\PeakCommands\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Artisan;
use Statamic\Console\RunsInPlease;
use Studio1902\PeakCommands\Commands\Traits\Operations;
use Studio1902\PeakCommands\Models\Installable;
use Studio1902\PeakCommands\Registry;

class InstallBlock extends Command
{
    use RunsInPlease, SharedFunctions, NeedsValidLicense, Operations;

    protected $name = 'statamic:peak:install:block';
    protected $description = "Install premade blocks into your page builder.";

    protected array $choices = [];
    protected ?Collection $items = null;

    public function handle(): void
    {
        $this->checkLicense();

        $this->loadItems(Registry::BLOCKS);

        $this->collectChoices(
            label: 'Which blocks do you want to install into your page builder?',
            emptyValidation: 'Please select at least one block. (Space)',
            type: Registry::BLOCKS,
        );

        $this->installChoices();
    }

    protected function installChoices(): void
    {
        collect($this->choices)->each(function (string $handle) {
            $installable = app(Installable::class, ['config' => $this->items->get($handle)])->install();

            $this->info("<info>[✓]</info> Peak page builder block '$installable->name' installed.");
        });

        Artisan::call('cache:clear');
    }
}
