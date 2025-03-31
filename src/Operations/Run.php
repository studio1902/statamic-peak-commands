<?php

namespace Studio1902\PeakCommands\Operations;

use Statamic\Support\Arr;
use Studio1902\PeakCommands\Models\Installable;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;
use function Laravel\Prompts\error;
use function Laravel\Prompts\info;
use function Laravel\Prompts\spin;

class Run extends Operation
{
    protected string $command;
    protected string $processingMessage;
    protected string $successMessage;
    protected string $errorMessage;
    protected bool $tty;
    protected bool $spinner;
    protected int $timeout;

    public function __construct(array $config)
    {
        $this->command = Arr::get($config, 'command');
        $this->processingMessage = Arr::get($config, 'processing_message', '');
        $this->successMessage = Arr::get($config, 'success_message', '');
        $this->errorMessage = Arr::get($config, 'error_message', '');
        $this->tty = Arr::get($config, 'tty', false);
        $this->spinner = Arr::get($config, 'spinner', true);
        $this->timeout = Arr::get($config, 'timeout', 120);
    }

    public function run(): Installable
    {
        $process = new Process(explode(' ', $this->command));
        $process->setTimeout($this->timeout);
        $process->setTty($this->tty);


        try {
            $this->spinner ?
                $this->withSpinner(
                    fn() => $process->mustRun(),
                    $this->processingMessage,
                    $this->successMessage
                ) :
                $this->withoutSpinner(
                    fn() => $process->mustRun(),
                    $this->successMessage
                );

            return $this->installable;
        } catch (ProcessFailedException $exception) {
            error($this->errorMessage ?: $exception->getMessage());
            exit();
        }
    }

    protected function withSpinner(callable $callback, string $processingMessage = '', string $successMessage = ''): void
    {
        spin($callback, $processingMessage);

        if ($successMessage) {
            info("[✓] $successMessage");
        }
    }

    protected function withoutSpinner(callable $callback, string $successMessage = ''): void
    {
        $callback();

        if ($successMessage) {
            info("[✓] $successMessage");
        }
    }
}
