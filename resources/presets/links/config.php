<?php

return [
    'handle' => 'links',
    'name' => 'Links',
    'description' => 'A LinkTree type blueprint and template.',
    'operations' => [
        [
            'type' => 'copy',
            'input' => 'resources/blueprints/collections/pages/links.yaml',
            'output' => 'resources/blueprints/collections/pages/links.yaml',
        ],
        [
            'type' => 'copy',
            'input' => 'resources/svg/phosphor-fill.icons',
            'output' => 'resources/svg/phosphor-fill.zip',
        ],
        [
            'type' => 'run',
            'command' => 'unzip resources/svg/phosphor-fill.zip -d resources/svg',
            'processing_message' => 'Unzipping icons',
            'success_message' => 'Unzipping icons.',
        ],
        [
            'type' => 'copy',
            'input' => 'resources/views/links.antlers.html',
            'output' => 'resources/views/links.antlers.html',
        ],
    ],
];
