<?php

namespace Studio1902\PeakCommands\Models;

use Statamic\Facades\Config;
use Stringy\StaticStringy as Stringy;
use function Laravel\Prompts\select;
use function Laravel\Prompts\text;

class Partial
{
    public string $name;
    public string $description;
    public string $type;
    public string $folder;
    public string $filename;

    public function __construct(array $config = [])
    {
        $this->type = $config['type'] ?? $this->promptForType();
        $this->name = $config['name'] ?? $this->promptForName();
        $this->description = $config['description'] ?? $this->promptForDescription();
        $this->filename = $this->generateFilename();
        $this->folder = $this->generateFolderName();
    }

    protected function promptForName(): string
    {
        return text(
            label: 'What should be the name for this partial?',
            placeholder: 'E.g. Card',
            required: true
        );
    }

    protected function promptForDescription(): string
    {
        return text(
            label: 'What should be the description for this partial?',
            placeholder: 'E.g. A card component.',
            required: true
        );
    }

    protected function generateFilename(): string
    {
        return Stringy::slugify($this->name, '_', Config::getShortLocale());
    }

    protected function promptForType(): string
    {
        return select(
            label: 'What type of partial do you want to add?',
            options: ['Component', 'Layout', 'Snippet', 'Typography'],
            default: 'Component'
        );
    }

    protected function generateFolderName(): string
    {
        $folder = strtolower($this->type);

        if (in_array($folder, ['component', 'snippet'])) {
            $folder = $folder . 's';
        }

        return $folder;
    }
}
