<?php

namespace Studio1902\PeakCommands\Operations;

use Illuminate\Support\Str;
use Studio1902\PeakCommands\Models\Installable;
use Studio1902\PeakCommands\Registry;

abstract class Operation
{
    protected Installable $installable;

    public static function resolve(string $class, array $config): Operation
    {
        if (Str::contains($class, '\\')) {
            return app($class, ['config' => $config]);
        }

        $className = collect(Registry::getNamespaces())
            ->map(fn(string $namespace) => $namespace . '\\' . Str::studly($class))
            ->filter(fn(string $class) => class_exists($class))
            ->first();

        return app($className, ['config' => $config]);
    }

    public function hydrate(Installable $installable): self
    {
        $this->installable = $installable;

        return $this;
    }

    abstract public function run(): Installable;
}
