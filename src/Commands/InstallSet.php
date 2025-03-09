<?php

namespace Studio1902\PeakCommands\Commands;

use Statamic\Console\RunsInPlease;
use Studio1902\PeakCommands\Models\Installable;
use Studio1902\PeakCommands\Registry;
use function Laravel\Prompts\info;

class InstallSet extends InstallCommand
{
    use RunsInPlease;

    protected $name = 'statamic:peak:install:set';
    protected $description = "Install premade sets into your article field.";

    protected string $type = Registry::SETS;

    public function handle(): void
    {
        $this->handleInstallation(
            label: 'Which sets do you want to install into your article field?',
            emptyValidation: 'Please select at least one set. (Space)',
            successMessage: fn(Installable $installable) => info("<info>[✓]</info> Peak Article Set '$installable->name' installed.")
        );
    }
}
