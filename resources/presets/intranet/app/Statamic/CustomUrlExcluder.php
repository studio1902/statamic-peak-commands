<?php

namespace App\Statamic;

use Statamic\Contracts\Entries\Entry;
use Statamic\Facades\Data;
use Statamic\StaticCaching\DefaultUrlExcluder;

class CustomUrlExcluder extends DefaultUrlExcluder
{
    public function isExcluded(string $url): bool
    {
        if (parent::isExcluded($url)) {
            return true;
        }

        $entry = Data::findByRequestUrl($url);

        if (! $entry instanceof Entry) {
            return false;
        }

        return preg_match_all('/"exclude_from_static_caching":(true|"true")/', $entry->values()->toJson());
    }
}
