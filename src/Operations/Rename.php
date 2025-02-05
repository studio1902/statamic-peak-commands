<?php

namespace Studio1902\PeakCommands\Operations;

use Illuminate\Support\Str;
use Studio1902\PeakCommands\Models\Installable;
use function Laravel\Prompts\text;

class Rename extends Operation
{
    public function __construct(array $config)
    {
    }

    public function run(): Installable
    {
        $this->installable->rename = true;
        $this->installable->renameName = text(
            label: "What should be the collection name for '{$this->installable->name}'?",
            placeholder: "E.g. '{$this->installable->name}'",
            required: true
        );

        $this->installable->renameHandle = Str::slug($this->installable->renameName, '_');

        $this->installable->renameSingularName = ucfirst(text(
            label: "What is the singular name for this '{$this->installable->renameName}' collection?",
            placeholder: "E.g. '{$this->installable->singularName}'",
            required: true
        ));

        $this->installable->renameSingularHandle = Str::slug($this->installable->renameSingularName, '_');

        return $this->installable;
    }
}
