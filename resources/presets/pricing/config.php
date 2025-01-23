<?php

return [
    'handle' => 'pricing',
    'name' => 'Pricing tiers & features',
    'description' => 'Create and list pricing tiers and feature tables.',
    'operations' => [
        [
            'type' => 'run',
            'command' => 'composer require stillat/relationships',
            'processing_message' => 'Installing stillat/relationships',
            'success_message' => 'Relationships installed.'
        ],
        [
            'type' => 'copy',
            'input' => 'app/Scopes/Multiselect.php.stub',
            'output' => 'app/Scopes/Multiselect.php'
        ],
        [
            'type' => 'copy',
            'input' => 'content/collections/features.yaml.stub',
            'output' => 'content/collections/features.yaml'
        ],
        [
            'type' => 'copy',
            'input' => 'content/collections/tiers.yaml.stub',
            'output' => 'content/collections/tiers.yaml'
        ],
        [
            'type' => 'copy',
            'input' => 'content/taxonomies/groups.yaml.stub',
            'output' => 'content/taxonomies/groups.yaml'
        ],
        [
            'type' => 'copy',
            'input' => 'resources/blueprints/collections/features/feature.yaml.stub',
            'output' => 'resources/blueprints/collections/features/feature.yaml'
        ],
        [
            'type' => 'copy',
            'input' => 'resources/blueprints/collections/tiers/tier.yaml.stub',
            'output' => 'resources/blueprints/collections/tiers/tier.yaml'
        ],
        [
            'type' => 'copy',
            'input' => 'resources/blueprints/taxonomies/groups/group.yaml.stub',
            'output' => 'resources/blueprints/taxonomies/groups/group.yaml'
        ],
        [
            'type' => 'copy',
            'input' => 'resources/fieldsets/features.yaml.stub',
            'output' => 'resources/fieldsets/features.yaml'
        ],
        [
            'type' => 'copy',
            'input' => 'resources/fieldsets/tiers.yaml.stub',
            'output' => 'resources/fieldsets/tiers.yaml'
        ],
        [
            'type' => 'copy',
            'input' => 'resources/svg/check.svg.stub',
            'output' => 'resources/svg/check.svg'
        ],
        [
            'type' => 'copy',
            'input' => 'resources/svg/cross.svg.stub',
            'output' => 'resources/svg/cross.svg'
        ],
        [
            'type' => 'copy',
            'input' => 'resources/views/page_builder/_features.antlers.html.stub',
            'output' => 'resources/views/page_builder/_features.antlers.html'
        ],
        [
            'type' => 'copy',
            'input' => 'resources/views/page_builder/_tiers.antlers.html.stub',
            'output' => 'resources/views/page_builder/_tiers.antlers.html'
        ],
        [
            'type' => 'update_page_builder',
            'block' => [
                'name' => 'Features',
                'instructions' => 'List a pricing tier feature table.',
                'icon' => 'file-content-list',
                'handle' => 'features',
            ]
        ],
        [
            'type' => 'update_page_builder',
            'block' => [
                'name' => 'Tiers',
                'instructions' => 'List pricing tiers.',
                'icon' => 'money-graph-bar-increase',
                'handle' => 'tiers',
            ]
        ],
        [
            'type' => 'update_role',
            'role' => 'editor',
            'permissions' => ['view features entries', 'edit features entries', 'create features entries', 'delete features entries', 'publish features entries', 'reorder features entries', 'edit other authors features entries', 'publish other authors features entries', 'delete other authors features entries', 'view tiers entries', 'edit tiers entries', 'create tiers entries', 'delete tiers entries', 'publish tiers entries', 'reorder tiers entries', 'edit other authors tiers entries', 'publish other authors tiers entries', 'delete other authors tiers entries', 'view groups terms', 'edit groups terms', 'create groups terms', 'delete groups terms']
        ],
        [
            'type' => 'notify',
            'content' => "Make sure to add the following to boot method of your AppServiceProvider.php:\n\n// Bi-direcitonal relation between tears and features.\n\Stillat\Relationships\Support\Facades\Relate::manyToMany(\n\t'tiers.features',\n\t'features.tiers'\n);"
        ],
        [
            'type' => 'notify',
            'content' => "Add this to your `lang/locale/strings.php` file:\n\n// Pricing and features\n'pricing_title' => 'Plans and included features',\n'pricing_included_sr' => 'Included in :tier tier.',\n'pricing_excluded_sr' => 'Excluded in :tier tier.',"
        ],
        [
            'type' => 'notify',
            'content' => "Optionally add the following to the boot method your AppServiceProvider.php if you want to group pricing related content in the CP navigation:\n\n// Add Pricing CP nav\n\Statamic\Facades\CP\Nav::extend(function (\$nav) {\n\t\$nav->content('Pricing')\n\t\t->icon('pro-ribbon');\n});"
        ],
        [
            'type' => 'notify',
            'content' => "When you want to group your pricing related content, also add to or create and add to `resources/preferences.yaml`:\nnav:\n\040content:\n\040\040reorder: true\n\040\040items:\n\040\040\040'content::collections': '@inherit'\n\040\040\040'content::pricing':\n\040\040\040\040action: '@modify'\n\040\040\040\040url: collections/tiers\n\040\040\040\040children:\n\040\040\040\040\040'content::collections::tiers': '@move'\n\040\040\040\040\040'content::collections::features': '@move'\n\040\040\040\040\040'content::taxonomies::groups': '@move'\n\040\040\040'content::navigation': '@inherit'\n\040\040\040'content::taxonomies': '@inherit'\n\040\040\040'content::assets':\n\040\040\040\040action: '@modify'\n\040\040\040\040reorder: true\n\040\040\040\040children:\n\040\040\040\040\040'content::assets::images': '@inherit'\n\040\040\040\040\040'content::assets::files': '@inherit'\n\040\040\040\040\040'content::assets::social_images': '@inherit'"
        ],
    ]
];
