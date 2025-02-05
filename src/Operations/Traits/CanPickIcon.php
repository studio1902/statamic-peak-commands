<?php

namespace Studio1902\PeakCommands\Operations\Traits;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use ReflectionClass;
use Statamic\Fieldtypes\Sets;
use function Laravel\Prompts\search;

trait CanPickIcon
{
    protected function pickIcon(string $label)
    {
        $reflection = new ReflectionClass(Sets::class);
        $iconsDirectory = $reflection->getStaticPropertyValue('iconsDirectory') ?? base_path('/vendor/statamic/cms/resources/svg/icons');
        $iconsFolder = $reflection->getStaticPropertyValue('iconsFolder');

        $icons = collect(File::allFiles("$iconsDirectory/$iconsFolder"))
            ->map(fn($file) => str_replace('.svg', '', $file->getBasename('.' . $file->getExtension())));

        if (DIRECTORY_SEPARATOR === '\\') {
            return $icons->first();
        }

        return search(
            label: $label,
            options: function (string $value) use ($icons) {
                if (!$value) {
                    return $icons
                        ->values()
                        ->all();
                }

                return $icons
                    ->filter(fn(string $item) => Str::contains($item, $value, true))
                    ->values()
                    ->all();
            },
            placeholder: 'file-content-list',
            scroll: 10,
            required: true
        );
    }
}
