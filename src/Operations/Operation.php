<?php

namespace Studio1902\PeakCommands\Operations;

use Studio1902\PeakCommands\Models\Installable;

abstract class Operation
{
    protected Installable $installable;

    public function hydrate(Installable $installable): self
    {
        $this->installable = $installable;

        return $this;
    }

    abstract public function run(): Installable;
}
