<?php

namespace Studio1902\PeakCommands\Commands;

use Statamic\Console\RunsInPlease;
use Studio1902\PeakCommands\Models\Installable;
use Studio1902\PeakCommands\Registry;

use function Laravel\Prompts\info;

class InstallBlock extends InstallCommand
{
    use RunsInPlease;

    protected $name = 'statamic:peak:install:block';

    protected $description = 'Install pre-made blocks into your page builder.';

    protected string $type = Registry::BLOCKS;

    public function handle(): void
    {
        $this->handleInstallation(
            label: 'Which blocks do you want to install into your page builder?',
            emptyValidation: 'Please select at least one block. (Space)',
            successMessage: fn (Installable $installable) => info("<info>[✓]</info> Peak page builder block '$installable->name' installed.")
        );
    }
}
