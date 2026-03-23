<?php

return [
    'handle' => 'audio_self_hosted',
    'name' => 'Audio self hosted',
    'description' => 'Add a self hosted audio stream.',
    'operations' => [
        [
            'type' => 'copy',
            'input' => '_audio_self_hosted.antlers.html',
            'output' => 'resources/views/components/_audio_self_hosted.antlers.html',
        ],
        [
            'type' => 'copy',
            'input' => 'audio_self_hosted.yaml',
            'output' => 'resources/fieldsets/audio_self_hosted.yaml',
        ],
        [
            'type' => 'update_article_sets',
            'set' => [
                'name' => 'Audio self hosted',
                'icon' => 'media-music-notes',
                'instructions' => 'Add a self hosted audio stream.',
                'handle' => 'audio_self_hosted',
            ],
        ],
    ],
];
