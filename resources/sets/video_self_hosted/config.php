<?php

return [
    'handle' => 'video_self_hosted',
    'name' => 'Video self hosted',
    'description' => 'Add a self hosted video.',
    'operations' => [
        [
            'type' => 'copy',
            'input' => '_video_self_hosted.antlers.html',
            'output' => 'resources/vies/page_builder/_video_self_hosted.antlers.html'
        ],
        [
            'type' => 'update_article_sets',
            'block' => [
                'name' => 'Video self hosted',
                'icon' => 'media-webcam-video',
                'description' => 'Add a self hosted video.',
                'handle' => 'video_self_hosted',
            ]
        ],
    ]
];
