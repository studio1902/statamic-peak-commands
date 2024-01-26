<?php

namespace Studio1902\PeakCommands\Updates;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Statamic\UpdateScripts\UpdateScript;

class UpdateRSSFeed extends UpdateScript
{
    public function shouldUpdate($newVersion, $oldVersion)
    {
        return $this->isUpdatingTo('4.3.0');
    }

    public function update()
    {
        $view = base_path("resources/views/feed/feed.antlers.xml");

        if (File::exists($view)) {
            $contents = Str::of(File::get($view))
                ->replace('{{ now iso_format="ddd, DD MMM GGGG HH:mm:ss UT" }}', "{{ now | format('r') }}")
                ->replace('{{ date iso_format="ddd, DD MMM GGGG hh:mm:ss UT" }}', "{{ date | format('r') }}")
                ->replace("{{ glide:image preset='lg' }}", '{{ glide:image width="1200" height="900" fit="crop_focal" }}');

            File::put($view, $contents);

            $this->console()->info('Use proper ISO formatting for dates, and crop image in your RSS feed template.');
        }
    }
}
