<?php

namespace Studio1902\PeakCommands\Models;

use Statamic\Facades\Config;
use Statamic\Support\Arr;
use Stringy\StaticStringy as Stringy;

use function Laravel\Prompts\confirm;
use function Laravel\Prompts\text;

class Globals
{
    public string $name;

    public string $filename;

    public bool $grantPermissions;

    public function __construct(array $config = [])
    {
        $this->name = Arr::get($config, 'name') ?? $this->promptForName();
        $this->grantPermissions = Arr::get($config, 'permissions') ?? $this->promptForPermissions();
        $this->filename = $this->generateFilename();
    }

    protected function promptForName(): string
    {
        return text(
            label: 'What should be the name for this global?',
            placeholder: 'E.g. Contact data',
            required: true
        );
    }

    protected function generateFilename(): string
    {
        return Stringy::slugify($this->name, '_', Config::getShortLocale());
    }

    protected function promptForPermissions(): bool
    {
        return confirm(
            label: 'Grant edit permissions to editor role?',
            default: true
        );
    }
}
