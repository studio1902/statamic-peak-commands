<?php

namespace Studio1902\PeakCommands\Operations;

use Illuminate\Support\Str;
use Studio1902\PeakCommands\Models\Installable;

abstract class Operation
{
    abstract public function __construct(array $config);

    public static function resolve(string $class, array $config): Operation
    {
        if (Str::startsWith($class, '\\')) {
            return app($class, ['config' => $config]);
        }

        $className = collect([
            '\App\PeakCommands\Operations',
            '\Studio1902\PeakCommands\Operations'
        ])
            ->map(fn(string $namespace) => $namespace . '\\' . Str::studly($class))
            ->filter(fn(string $class) => class_exists($class))
            ->first();

        return app($className, ['config' => $config]);
    }

    abstract public function run(Installable $installable): Installable;
}
