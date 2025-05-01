<?php

namespace Studio1902\PeakCommands\Operations;

use Illuminate\Support\Str;
use Statamic\Support\Arr;
use Studio1902\PeakCommands\Models\Installable;

use function Laravel\Prompts\pause;
use function Laravel\Prompts\warning;

class Notify extends Operation
{
    protected string $content;

    public function __construct(array $config)
    {
        $this->content = Arr::get($config, 'content');
    }

    public function run(): Installable
    {
        $message = (string) Str::of($this->content)
            ->replace('{{ handle }}', $this->installable->renameHandle)
            ->replace('{{ name }}', $this->installable->renameName);

        warning("\n".$message."\n");

        pause('Follow the instructions and press ENTER to continue.');

        return $this->installable;
    }
}
