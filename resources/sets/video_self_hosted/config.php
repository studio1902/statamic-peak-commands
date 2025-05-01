<?php

return [
    'handle' => 'video_self_hosted',
    'name' => 'Video self hosted',
    'description' => 'Add a self hosted video.',
    'operations' => [
        [
            'type' => 'copy',
            'input' => '_video_self_hosted.antlers.html',
            'output' => 'resources/views/components/_video_self_hosted.antlers.html',
        ],
        [
            'type' => 'copy',
            'input' => 'video_self_hosted.yaml',
            'output' => 'resources/fieldsets/video_self_hosted.yaml',
        ],
        [
            'type' => 'update_article_sets',
            'set' => [
                'name' => 'Video self hosted',
                'icon' => 'media-webcam-video',
                'instructions' => 'Add a self hosted video.',
                'handle' => 'video_self_hosted',
            ],
        ],
    ],
];
