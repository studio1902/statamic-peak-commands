<?php

namespace Studio1902\PeakCommands\Models;

class Block
{

    public string $name;
    public string $instructions;
    public string $icon;
    public string $handle;

    public function __construct(array $config)
    {
        $this->name = $config['name'];
        $this->instructions = $config['instructions'];
        $this->icon = $config['icon'];
        $this->handle = $config['handle'];
    }
}
