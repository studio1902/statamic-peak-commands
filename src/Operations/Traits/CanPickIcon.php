<?php

namespace Studio1902\PeakCommands\Operations\Traits;

use Illuminate\Support\Str;
use Statamic\Icons\IconManager;

use function Laravel\Prompts\search;

trait CanPickIcon
{
    protected function pickIcon(string $label)
    {
        $iconManager = app(IconManager::class);
        $iconSet = $iconManager->sets()->first() ?? $iconManager->default();

        return search(
            label: $label,
            options: function (string $value) use ($iconSet) {
                if (! $value) {
                    return $iconSet
                        ->names()
                        ->all();
                }

                return $iconSet
                    ->names()
                    ->filter(fn (string $item) => Str::contains($item, $value, true))
                    ->values()
                    ->all();
            },
            placeholder: 'file-content-list',
            scroll: 10,
            required: true
        );
    }
}
