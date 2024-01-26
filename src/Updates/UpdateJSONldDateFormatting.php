<?php

namespace Studio1902\PeakCommands\Updates;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Statamic\UpdateScripts\UpdateScript;

class UpdateJSONldDateFormatting extends UpdateScript
{
    public function shouldUpdate($newVersion, $oldVersion)
    {
        return $this->isUpdatingTo('4.4.0');
    }

    public function update()
    {
        $disk = Storage::build([
            'driver' => 'local',
            'root' => resource_path('/views'),
        ]);

        collect($disk->allFiles())
            ->filter(function($file) use ($disk) {
                return Str::contains($disk->get($file), 'format="Y-m-d\TH:i:s"');
            })
            ->each(function ($file) use ($disk) {
                $contents = Str::of($disk->get($file))
                    ->replace('format="Y-m-d\TH:i:s"', "| format('c')");

                $disk->put($file, $contents);

                $this->console()->info("Replaced `format=\"Y-m-d\TH:i:s\"` with `| format('c')` in `$file`.");
            }
        );
    }
}
