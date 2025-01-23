<?php

return [
    'handle' => 'footer',
    'name' => 'Footer',
    'description' => 'A mega footer with multiple navigations.',
    'operations' => [
        [
            'type' => 'copy',
            'input' => 'resources/views/layout/_footer.antlers.html.stub',
            'output' => 'resources/views/layout/_footer.antlers.html'
        ],
        [
            'type' => 'copy',
            'input' => 'content/navigation/footer.yaml.stub',
            'output' => 'content/navigation/footer.yaml'
        ],
        [
            'type' => 'copy',
            'input' => 'content/navigation/legal.yaml.stub',
            'output' => 'content/navigation/legal.yaml'
        ],
        [
            'type' => 'copy',
            'input' => 'resources/blueprints/navigation/footer.yaml.stub',
            'output' => 'resources/blueprints/navigation/footer.yaml'
        ],
        [
            'type' => 'copy',
            'input' => 'resources/blueprints/navigation/legal.yaml.stub',
            'output' => 'resources/blueprints/navigation/legal.yaml'
        ],
        [
            'type' => 'copy',
            'input' => 'resources/blueprints/navigation/footer.yaml.stub',
            'output' => 'resources/blueprints/navigation/footer.yaml'
        ],
        [
            'type' => 'update_role',
            'role' => 'editor',
            'permissions' => ['view footer nav', 'edit footer nav', 'view legal nav', 'edit legal nav']
        ],
        [
            'type' => 'notify',
            'content' => "Add this to your `lang/locale/strings.php` file:\n\n// Footer\n'privacy_statement' => 'Privacy statement',\n'cookie_notice' => 'Cookie notice',"
        ],
    ],
];
