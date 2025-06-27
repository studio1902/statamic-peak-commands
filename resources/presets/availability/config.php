<?php

return [
    'handle' => 'availability',
    'name' => 'Availability',
    'description' => 'A global and availability component.',
    'operations' => [
        [
            'type' => 'copy',
            'input' => 'resources/blueprints/globals/availability.yaml',
            'output' => 'resources/blueprints/globals/availability.yaml',
        ],
        [
            'type' => 'copy',
            'input' => 'content/globals/availability.yaml',
            'output' => 'content/globals/availability.yaml',
        ],
        [
            'type' => 'copy',
            'input' => 'resources/views/components/_availability.antlers.html',
            'output' => 'resources/views/components/_availability.antlers.html',
        ],
        [
            'type' => 'update_role',
            'role' => 'editor',
            'permissions' => ['edit availability globals'],
        ],
        [
            'type' => 'notify',
            'content' => 'Make sure to add `{{ partial:components/availability }}` where you want to render the component.',
        ],
        [
            'type' => 'notify',
            'content' => "Add this to your `lang/locale/strings.php` file:\n(and possibly any other locales you support)\n\n'availability' => 'available from :month',\n'availability_now' => 'available right now',",
        ],
    ],
];
