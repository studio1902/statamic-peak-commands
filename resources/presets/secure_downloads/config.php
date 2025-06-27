<?php

return [
    'handle' => 'secure_downloads',
    'name' => 'Secure downloads',
    'description' => 'Offer secure downloads.',
    'operations' => [
        [
            'type' => 'copy',
            'input' => 'app/Http/Controllers/DownloadController.php',
            'output' => 'app/Http/Controllers/DownloadController.php',
        ],
        [
            'type' => 'copy',
            'input' => 'app/Tags/Download.php',
            'output' => 'app/Tags/Download.php',
        ],
        [
            'type' => 'copy',
            'input' => 'resources/blueprints/assets/downloads.yaml',
            'output' => 'resources/blueprints/assets/downloads.yaml',
        ],
        [
            'type' => 'copy',
            'input' => 'resources/fieldsets/downloads.yaml',
            'output' => 'resources/fieldsets/downloads.yaml',
        ],
        [
            'type' => 'copy',
            'input' => 'content/assets/downloads.yaml',
            'output' => 'content/assets/downloads.yaml',
        ],
        [
            'type' => 'copy',
            'input' => 'resources/views/page_builder/_downloads.antlers.html',
            'output' => 'resources/views/page_builder/_downloads.antlers.html',
        ],
        [
            'type' => 'update_page_builder',
            'block' => [
                'name' => 'Downloads',
                'instructions' => 'Offer secured downloads.',
                'icon' => 'download-arrow-down',
                'handle' => 'downloads',
            ],
        ],
        [
            'type' => 'update_role',
            'role' => 'editor',
            'permissions' => ['view downloads assets', 'upload downloads assets', 'edit downloads assets', 'move downloads assets', 'rename downloads assets', 'delete downloads assets'],
        ],
        [
            'type' => 'notify',
            'content' => "Add this to the `disks` array in your `config/filesystems.php` file:\n\n'downloads' => [\n\t'driver' => 'local',\n\t'root' => storage_path('downloads'),\n\t'serve' => true,\n\t'throw' => false,\n],",
        ],
        [
            'type' => 'notify',
            'content' => "Add this to your `routes/web.php` file:\n\n// Secure downloads.\nRoute::get('secure/download', [\App\Http\Controllers\DownloadController::class, '__invoke'])->middleware('statamic.web')->name('secure-download');",
        ],
        [
            'type' => 'notify',
            'content' => "Add this to your `paths` array to your `config/statamic/git.php` file:\n\nstorage_path('downloads'),",
        ],
        [
            'type' => 'notify',
            'content' => "Add this to your `lang/locale/strings.php` file:\n(and possibly any other locales you support)\n\n// Downloads\n'download' => 'Download',",
        ],
    ],
];
