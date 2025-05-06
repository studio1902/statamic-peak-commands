<?php

namespace Studio1902\PeakCommands\Commands\Traits;

use function Laravel\Prompts\error;

trait HandleWithCatch
{
    public function handle(): void
    {
        try {
            $this->handleWithCatch();
        } catch (\Exception $exception) {
            error($exception->getMessage());
            exit(1);
        }
    }

    abstract public function handleWithCatch();
}
