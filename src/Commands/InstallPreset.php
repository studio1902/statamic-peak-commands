<?php

namespace Studio1902\PeakCommands\Commands;

use Statamic\Console\RunsInPlease;
use Studio1902\PeakCommands\Models\Installable;
use Studio1902\PeakCommands\RegistryManager;

use function Laravel\Prompts\info;

class InstallPreset extends InstallCommand
{
    use RunsInPlease;

    protected $name = 'statamic:peak:install:preset';

    protected $description = 'Install premade collections and page builder blocks into your site.';

    protected string $type = RegistryManager::PRESETS;

    public function handle(): void
    {
        $this->handleInstallation(
            label: 'Which presets do you want to install into your site?',
            emptyValidation: 'Please select at least one preset. (Space)',
            successMessage: fn (Installable $installable) => info("[✓] Peak preset '$installable->name' installed.")
        );
    }
}
