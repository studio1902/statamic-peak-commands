<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Statamic\Facades\AssetContainer;
use Statamic\Facades\Path;
use Statamic\Http\Controllers\Controller;

class DownloadController extends Controller
{
    public function __invoke(Request $request)
    {
        if (! $request->hasValidSignature()) {
            abort(403);
        }

        return response()->download(Path::assemble(AssetContainer::find($request->input('container'))->diskPath(), $request->input('file')));
    }
}
