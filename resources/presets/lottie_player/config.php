<?php

return [
    'handle' => 'lottie_player',
    'name' => 'Lottie player',
    'description' => 'A Lottie animation player.',
    'operations' => [
        [
            'type' => 'run',
            'command' => 'npm i lottie-web',
            'processing_message' => 'Installing Lottie Web',
            'success_message' => 'Lottie Web installed.',
        ],
        [
            'type' => 'run',
            'command' => 'npm i @alpinejs/intersect',
            'processing_message' => 'Installing Alpine Intersect plugin',
            'success_message' => 'Alpine Intersect plugin installed.',
        ],
        [
            'type' => 'copy',
            'input' => 'resources/blueprints/assets/animations.yaml',
            'output' => 'resources/blueprints/assets/animations.yaml',
        ],
        [
            'type' => 'copy',
            'input' => 'resources/views/components/_animation.antlers.html',
            'output' => 'resources/views/components/_animation.antlers.html',
        ],
        [
            'type' => 'copy',
            'input' => 'content/assets/animations.yaml',
            'output' => 'content/assets/animations.yaml',
        ],
        [
            'type' => 'copy',
            'input' => 'resources/views/page_builder/_animation.antlers.html',
            'output' => 'resources/views/page_builder/_animation.antlers.html',
        ],
        [
            'type' => 'copy',
            'input' => 'resources/fieldsets/animation.yaml',
            'output' => 'resources/fieldsets/animation.yaml',
        ],
        [
            'type' => 'update_page_builder',
            'block' => [
                'name' => 'Animation',
                'instructions' => 'Render a Lottie animation.',
                'icon' => 'media-computer-screen-tv',
                'handle' => 'animation',
            ],
        ],
        [
            'type' => 'update_role',
            'role' => 'editor',
            'permissions' => ['view animations assets', 'upload animations assets', 'edit animations assets', 'move animations assets', 'rename animations assets', 'delete animations assets'],
        ],
        [
            'type' => 'notify',
            'content' => "Add this to the `disks` array in your `config/filesystems.php` file:\n\n'animations' => [\n\t'driver' => 'local',\n\t'root' => public_path('animations'),\n\t'visibility' => 'public',\n],",
        ],
        [
            'type' => 'notify',
            'content' => "Add this to your `resources/js/site.js` file:\n\nimport intersect from '@alpinejs/intersect'\nimport lottie from 'lottie-web'\n\n// Init Lottie\nwindow.lottie = lottie\n\nAlpine.plugin([collapse, focus, intersect, morph, persist, precognition])",
        ],
    ],
];
