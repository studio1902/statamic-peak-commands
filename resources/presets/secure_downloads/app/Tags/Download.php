<?php

namespace App\Tags;

use Illuminate\Support\Facades\URL;
use Statamic\Tags\Tags;

class Download extends Tags
{
    protected static $handle = 'download';

    public function index()
    {
        return URL::temporarySignedRoute('secure-download', now()->addMinutes(5),
            [
                'file' => $this->params->get('file'),
                'container' => $this->params->get('container')
            ]
        );
    }
}
