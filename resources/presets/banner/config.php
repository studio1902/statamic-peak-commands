<?php

return [
    'handle' => 'banner',
    'name' => 'Banner',
    'description' => 'A banner on top of your site users can click to hide.',
    'operations' => [
        [
            'type' => 'copy',
            'input' => 'resources/blueprints/globals/banner.yaml',
            'output' => 'resources/blueprints/globals/banner.yaml',
        ],
        [
            'type' => 'copy',
            'input' => 'content/globals/banner.yaml',
            'output' => 'content/globals/banner.yaml',
        ],
        [
            'type' => 'copy',
            'input' => 'resources/views/layout/_banner.antlers.html',
            'output' => 'resources/views/layout/_banner.antlers.html',
        ],
        [
            'type' => 'copy',
            'input' => 'resources/svg/close.svg',
            'output' => 'resources/svg/close.svg',
        ],
        [
            'type' => 'update_role',
            'role' => 'editor',
            'permissions' => ['edit banner globals'],
        ],
        [
            'type' => 'notify',
            'content' => 'Make sure to add `{{ partial:layout/banner }}` to your layout file after opening the `<body>`.',
        ],
        [
            'type' => 'notify',
            'content' => "Add this to your `lang/locale/strings.php` file:\n(and possibly any other locales you support)\n\n// Banner\n'banner_close' => 'Close banner',",
        ],
    ],
];
