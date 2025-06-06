<?php

return [
    'handle' => 'hero',
    'name' => 'Hero',
    'description' => 'A hero layout component.',
    'operations' => [
        [
            'type' => 'copy',
            'input' => 'resources/fieldsets/hero.yaml',
            'output' => 'resources/fieldsets/hero.yaml',
        ],
        [
            'type' => 'copy',
            'input' => 'resources/views/layout/_hero.antlers.html',
            'output' => 'resources/views/layout/_hero.antlers.html',
        ],
        [
            'type' => 'notify',
            'content' => "Add this to each collection blueprint you want to render a hero on:\n\nhero:\n\tsections:\n\t\t-\n\t\t\tdisplay: Hero\n\t\t\tfields:\n\t\t\t\t-\n\t\t\t\t\timport: hero\n\t\t\t\t\tprefix: hero_",
        ],
        [
            'type' => 'notify',
            'content' => "Add this to your `resources/views/layout.antlers.html`:\n\n{{ partial:layout/hero :when=\"hero_render\" }}",
        ],
    ],
];
