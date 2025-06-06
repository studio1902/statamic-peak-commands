<?php

return [
    'handle' => 'breadcrumbs',
    'name' => 'Breadcrumbs',
    'description' => 'A breadcrumbs partial.',
    'operations' => [
        [
            'type' => 'copy',
            'input' => 'resources/views/navigation/_breadcrumbs.antlers.html',
            'output' => 'resources/views/navigation/_breadcrumbs.antlers.html',
        ],
    ],
];
