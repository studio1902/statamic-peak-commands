<?php

namespace Studio1902\PeakCommands\Models;

use Statamic\Facades\Config;
use Stringy\StaticStringy as Stringy;
use Studio1902\PeakCommands\Operations\Traits\CanPickIcon;
use function Laravel\Prompts\text;

class Block
{
    use CanPickIcon;

    public string $name;
    public string $handle;
    public string $instructions;
    public string $icon;

    public function __construct(array $config = [])
    {
        $this->name = $config['name'] ?? $this->promptForName();
        $this->handle = $config['handle'] ?? $this->promptForHandle();
        $this->instructions = $config['instructions'] ?? $this->promptForInstructions();
        $this->icon = $config['icon'] ?? $this->promptForIcon();
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'handle' => $this->handle,
            'instructions' => $this->instructions,
            'icon' => $this->icon,
        ];
    }

    protected function promptForName(): string
    {
        return text(
            label: 'What should be the name for this block?',
            placeholder: 'E.g. Text and image',
            required: true
        );
    }

    protected function promptForHandle(): string
    {
        return text(
            label: 'What should be the handle for this block?',
            default: Stringy::slugify($this->name, '_', Config::getShortLocale()),
            required: true
        );
    }

    protected function promptForInstructions(): string
    {
        return text(
            label: 'What should be the instructions for this block?',
            placeholder: 'E.g. Renders text and an image.',
            required: true
        );
    }

    protected function promptForIcon(): string
    {
        return $this->pickIcon('Which icon do you want to use for this block?');
    }
}
