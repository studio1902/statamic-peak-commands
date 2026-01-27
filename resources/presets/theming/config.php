<?php

return [
    'handle' => 'theming',
    'name' => 'Theming',
    'description' => 'Scaffolding for page and/or block based color theming.',
    'operations' => [
        [
            'type' => 'run',
            'command' => 'composer require rias/statamic-color-swatches',
            'processing_message' => 'Installing rias/statamic-color-swatches',
            'success_message' => 'Color Swatches installed.',
        ],
        [
            'type' => 'copy',
            'input' => 'resources/css/theme.css',
            'output' => 'resources/css/theme.css',
        ],
        [
            'type' => 'notify',
            'content' => "Add this to your `resources/css/site.css` file:\n@import \"./theme.css\";",
        ],
        [
            'type' => 'notify',
            'content' => "Define your theme variables and colors in `resources/css/theme.css`.",
        ],
        [
            'type' => 'notify',
            'content' => "Add this to your `resources/fieldsets/common.yaml` file and alter it to define your themes:\n-\n\thandle: theme\n\tfield:\n\t\tcolors:\n\t\t\t-\n\t\t\t\tid: mk70y5xf\n\t\t\t\tlabel: primary\n\t\t\t\tvalue:\n\t\t\t\t\t- '#0000ff'\n\t\t\t-\n\t\t\t\tid: mk70yepa\n\t\t\t\tvalue:\n\t\t\t\t\t- '#ffff03'\n\t\t\t\tlabel: secondary\n\t\tdefault: primary\n\t\ttype: color_swatches\n\t\tdisplay: Theme",
        ],
        [
            'type' => 'notify',
            'content' => "Add this to your the blueprints and/or block fieldsets that require theming:\n-\n\thandle: theme\n\tfield: common.theme\n\tconfig:\n\t\tvalidate:\n\t\t\t- required\n\t\tlistable: true",
        ],
        [
            'type' => 'notify',
            'content' => "Add this to the body tag in `resources/views/layout.antlers.html` when you use blueprint based theming:\n`data-theme=\"{{ page:theme:label }}\"`.",
        ],
        [
            'type' => 'notify',
            'content' => "Add this to the blocks, or block wrapper for blocks that require theming:\n`{{ block:theme ?= 'data-theme=\"{ block:theme.label }\"' }}`.",
        ],
    ],
];
