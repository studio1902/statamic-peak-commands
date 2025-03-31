<?php

namespace Studio1902\PeakCommands\Commands\Traits;

use Illuminate\Support\Facades\Artisan;

trait CanClearCache
{
    protected function clearCache(): void
    {
        Artisan::call('cache:clear');
    }

    protected function clearGlideCache(): void
    {
        Artisan::call('statamic:glide:clear');
    }
}
