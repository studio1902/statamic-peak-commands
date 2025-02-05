<?php

return [
    'handle' => 'mega_menu',
    'name' => 'Mega Menu',
    'description' => 'A three level deep mega dropdown menu.',
    'operations' => [
        [
            'type' => 'notify',
            'content' => "This preset assumes you still have a navigation called `main`, separated into the two default Peak templates: mobile and desktop."
        ],
        [
            'type' => 'copy',
            'input' => 'content/navigation/main.yaml',
            'output' => 'content/navigation/main.yaml'
        ],
        [
            'type' => 'copy',
            'input' => 'resources/blueprints/navigation/main.yaml',
            'output' => 'resources/blueprints/navigation/main.yaml'
        ],
        [
            'type' => 'copy',
            'input' => 'resources/views/navigation/_main_desktop.antlers.html',
            'output' => 'resources/views/navigation/_main_desktop.antlers.html'
        ],
        [
            'type' => 'copy',
            'input' => 'resources/views/navigation/_main_mobile.antlers.html',
            'output' => 'resources/views/navigation/_main_mobile.antlers.html'
        ],
        [
            'type' => 'notify',
            'content' => "Make sure to add items, section headers and pages with descriptions to your navigation first."
        ],
    ]
];
