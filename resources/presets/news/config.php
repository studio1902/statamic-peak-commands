<?php

return [
    'handle' => 'news',
    'name' => 'News',
    'singular_name' => 'Item',
    'description' => 'A dated renamable news/blog collection with feed.',
    'operations' => [
        [
            'type' => 'rename',
        ],
        [
            'type' => 'copy',
            'input' => 'resources/views/feed/feed.antlers.xml',
            'output' => 'resources/views/feed/feed.antlers.xml',
        ],
        [
            'type' => 'copy',
            'input' => 'resources/views/page_builder/_index_content.antlers.html',
            'output' => 'resources/views/page_builder/_index_content.antlers.html',
            'skippable' => true,
        ],
        [
            'type' => 'copy',
            'input' => 'resources/fieldsets/index_content.yaml',
            'output' => 'resources/fieldsets/index_content.yaml',
            'skippable' => true,
        ],
        [
            'type' => 'copy',
            'input' => 'resources/views/news/index.antlers.html',
            'output' => 'resources/views/{{ handle }}/index.antlers.html',
        ],
        [
            'type' => 'copy',
            'input' => 'resources/blueprints/collections/news/news.yaml',
            'output' => 'resources/blueprints/collections/{{ handle }}/{{ handle }}.yaml',
        ],
        [
            'type' => 'copy',
            'input' => 'content/collections/news.yaml',
            'output' => 'content/collections/{{ handle }}.yaml',
        ],
        [
            'type' => 'copy',
            'input' => 'resources/fieldsets/news.yaml',
            'output' => 'resources/fieldsets/{{ handle }}.yaml',
        ],
        [
            'type' => 'copy',
            'input' => 'resources/views/components/_news_item.antlers.html',
            'output' => 'resources/views/components/_{{ handle }}_item.antlers.html',
        ],
        [
            'type' => 'copy',
            'input' => 'resources/views/page_builder/_news.antlers.html',
            'output' => 'resources/views/page_builder/_{{ handle }}.antlers.html',
        ],
        [
            'type' => 'copy',
            'input' => 'content/collections/pages/news.md',
            'output' => 'content/collections/pages/{{ multisite_handle }}/{{ handle }}.md',
        ],
        [
            'type' => 'copy',
            'input' => 'resources/views/news/show.antlers.html',
            'output' => 'resources/views/{{ handle }}/show.antlers.html',
        ],
        [
            'type' => 'update_page_builder',
            'block' => [
                'name' => 'Index content',
                'instructions' => 'Render the currently mounted entries if available.',
                'icon' => 'file-content-list',
                'handle' => 'index_content',
            ],
        ],
        [
            'type' => 'update_page_builder',
            'block' => [
                'name' => '{{ name }}',
                'instructions' => 'List the most recent {{ name }}.',
                'icon' => 'share-mega-phone',
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
            'content' => "Add this to your `lang/locale/strings.php` file:\n(and possibly any other locales you support)\n\n// {{ name }}\n'{{ handle }}_all' => 'All articles',\n'{{ handle }}_more' => 'More articles',\n'{{ handle }}_read_more' => 'Read more',",
        ],
        [
            'type' => 'notify',
            'content' => "Add this to your `config/statamic/cp.php` widgets array:\n\n[\n\t'type' => 'collection',\n\t'collection' => '{{ handle }}',\n\t'width' => 50\n],",
        ],
        [
            'type' => 'notify',
            'content' => "Add this to your `routes/web.php` file:\n\n// The route to the RSS feed.\nRoute::statamic('/feed/{{ handle }}', 'feed/feed', [\n\t'layout' => null,\n\t'content_type' => 'application/xml',\n]);",
        ],
        [
            'type' => 'notify',
            'content' => "Add this to the `<head>` in your `views/layout.antlers.html` file:\n\n<link rel=\"alternate\" type=\"application/rss+xml\" title=\"{{ name }} Feed\" href=\"{{ config:app:url }}/feed/{{ handle }}\"/>",
        ],
        [
            'type' => 'notify',
            'content' => "Add this to the `exclude` array in your `config/statamic/static_caching.php` file:\n\n'/feed*',",
        ],
    ],
];
