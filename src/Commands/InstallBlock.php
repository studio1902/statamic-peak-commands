<?php

namespace Studio1902\PeakCommands\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;
use Statamic\Console\RunsInPlease;
use Studio1902\PeakCommands\Commands\Traits\Operations;

class InstallBlock extends Command
{
    use RunsInPlease, SharedFunctions, NeedsValidLicense, Operations;

    protected $name = 'statamic:peak:install:block';
    protected $description = "Install premade blocks into your page builder.";

    protected bool $rename = false;
    protected string $rename_handle = '';
    protected string $rename_name = '';
    protected string $rename_singular_name = '';
    protected string $rename_singular_handle = '';
    protected array $choices = [];
    protected string $handle = '';
    protected ?Collection $items = null;

    public function handle(): void
    {
        $this->checkLicense();

        $this->loadItems('blocks');

        $this->collectChoices(
            label: 'Which blocks do you want to install into your page builder?',
            emptyValidation: 'Please select at least one block. (Space)',
        );

        $this->handleChoices();
    }

    protected function handleChoices(): void
    {
        collect($this->choices)->each(function ($choice, $key) {
            $this->handle = $choice;
            $item = $this->items->get($this->handle);

            collect($item['operations'])->each(function ($operation) use ($item) {
                $method = Str::camel('operation_' . $operation['type']);
                $this->$method($operation, $item);
            });

            $this->info("<info>[✓]</info> Peak page builder block '{$item['name']}' installed.");

            if ($key === array_key_last($this->choices)) {
                Artisan::call('cache:clear');
            }
        });
    }
}
