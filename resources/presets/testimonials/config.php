<?php

return [
    'handle' => 'testimonials',
    'name' => 'Testimonials',
    'singular_name' => 'Testimonial',
    'description' => 'A renamable testimonial collection.',
    'operations' => [
        [
            'type' => 'rename',
        ],
        [
            'type' => 'copy',
            'input' => 'content/collections/testimonials.yaml',
            'output' => 'content/collections/{{ handle }}.yaml',
        ],
        [
            'type' => 'copy',
            'input' => 'resources/blueprints/collections/testimonials/testimonials.yaml',
            'output' => 'resources/blueprints/collections/{{ handle }}/{{ handle }}.yaml',
        ],
        [
            'type' => 'copy',
            'input' => 'resources/fieldsets/testimonials.yaml',
            'output' => 'resources/fieldsets/{{ handle }}.yaml',
        ],
        [
            'type' => 'copy',
            'input' => 'resources/views/page_builder/_testimonials.antlers.html',
            'output' => 'resources/views/page_builder/_{{ handle }}.antlers.html',
        ],
        [
            'type' => 'update_page_builder',
            'block' => [
                'name' => '{{ name }}',
                'instructions' => 'List a selection or random {{ name }}.',
                'icon' => 'text-formatting-quotation',
                'handle' => '{{ handle }}',
            ],
        ],
        [
            'type' => 'update_role',
            'role' => 'editor',
            'permissions' => ['view {{ handle }} entries', 'edit {{ handle }} entries', 'create {{ handle }} entries', 'delete {{ handle }} entries', 'publish {{ handle }} entries', 'reorder {{ handle }} entries', 'edit other authors {{ handle }} entries', 'publish other authors {{ handle }} entries', 'delete other authors {{ handle }} entries'],
        ],
        [
            'type' => 'notify',
            'content' => "Add this to your `config/statamic/cp.php` widgets array:\n\n[\n\t'type' => 'collection',\n\t'collection' => '{{ handle }}',\n\t'width' => 50\n],",
        ],
    ],
];
