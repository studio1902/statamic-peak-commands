<?php

namespace Studio1902\PeakCommands\Commands;

trait InstallPresetPresets {

    public function getPresets() {
        $this->presets = collect([
            [
                'handle' => 'banner',
                'name' => 'Banner',
                'description' => 'A banner on top of your site users can click to hide.',
                'operations' => [
                    [
                        'type' => 'copy',
                        'input' => 'banner_blueprint.yaml.stub',
                        'output' => 'resources/blueprints/globals/banner.yaml'
                    ],
                    [
                        'type' => 'copy',
                        'input' => 'banner_global.yaml.stub',
                        'output' => 'content/globals/banner.yaml'
                    ],
                    [
                        'type' => 'copy',
                        'input' => 'banner.antlers.html.stub',
                        'output' => 'resources/views/layout/_banner.antlers.html'
                    ],
                    [
                        'type' => 'copy',
                        'input' => 'close.svg.stub',
                        'output' => 'resources/svg/close.svg'
                    ],
                    [
                        'type' => 'notify',
                        'content' => "Add this to your `lang/locale/strings.php` file:\n\n// Banner\n'banner_close' => 'Close banner',"
                    ],
                    [
                        'type' => 'notify',
                        'content' => "Make sure to add `{{ partial:layout/banner }}` to your layout file after opening the `<body>`."
                    ]
                ]
            ],
            [
                'handle' => 'breadcrumbs',
                'name' => 'Breadcrumbs',
                'description' => 'A breadcrumbs partial using schema markup.',
                'operations' => [
                    [
                        'type' => 'copy',
                        'input' => 'breadcrumbs.antlers.html.stub',
                        'output' => 'resources/views/navigation/_breadcrumbs.antlers.html'
                    ]
                ]
            ],
            [
                'handle' => 'business_hours',
                'name' => 'Business hours',
                'description' => 'Configure and list business hours, live open/not open component',
                'operations' => [
                    [
                        'type' => 'copy',
                        'input' => 'business_hours_blueprint.yaml.stub',
                        'output' => 'resources/blueprints/globals/business_hours.yaml'
                    ],
                    [
                        'type' => 'copy',
                        'input' => 'business_hours_global.yaml.stub',
                        'output' => 'content/globals/business_hours.yaml'
                    ],
                    [
                        'type' => 'copy',
                        'input' => 'business_hours_snippet.antlers.html.stub',
                        'output' => 'resources/views/snippets/_business_hours.antlers.html'
                    ],
                    [
                        'type' => 'copy',
                        'input' => 'business_hours.antlers.html.stub',
                        'output' => 'resources/views/components/_business_hours.antlers.html'
                    ],
                    [
                        'type' => 'copy',
                        'input' => 'call_now.antlers.html.stub',
                        'output' => 'resources/views/components/_call_now.antlers.html'
                    ],
                    [
                        'type' => 'copy',
                        'input' => 'phone.svg.stub',
                        'output' => 'resources/svg/phone.svg'
                    ],
                    [
                        'type' => 'notify',
                        'content' => "Add this to your `lang/locale/strings.php` file:\n\n// Business hours\n'business_hours_can_reach' => 'Available now',\n'business_hours_cant_reach' => 'Not available now',\n'business_hours_closed' => 'Closed',\n'business_hours_open_now' => 'Open now',\n'business_hours_closed_now' => 'Closed now',\n'business_hours_monday' => 'Monday',\n'business_hours_tuesday' => 'Tuesday',\n'business_hours_wednesday' => 'Wednesday',\n'business_hours_thursday' => 'Thursday',\n'business_hours_friday' => 'Friday',\n'business_hours_saturday' => 'Saturday',\n'business_hours_sunday' => 'Sunday',"
                    ],
                    [
                        'type' => 'notify',
                        'content' => "Make sure to set the correct timezone in `config/app.php`."
                    ],
                    [
                        'type' => 'notify',
                        'content' => "Import the following partials where needed: `{{ partial:components/business_hours }}` and `{{ partial:components/call_now }}`."
                    ]
                ]
            ],
            [
                'handle' => 'clients',
                'name' => 'Clients',
                'description' => 'A renamable client/partner collection.',
                'operations' => [
                    [
                        'type' => 'rename'
                    ],
                    [
                        'type' => 'copy',
                        'input' => 'clients_blueprint.yaml.stub',
                        'output' => 'resources/blueprints/collections/{{ handle }}/{{ handle }}.yaml'
                    ],
                    [
                        'type' => 'copy',
                        'input' => 'clients_collection.yaml.stub',
                        'output' => 'content/collections/{{ handle }}.yaml'
                    ],
                    [
                        'type' => 'copy',
                        'input' => 'clients.antlers.html.stub',
                        'output' => 'resources/views/page_builder/_{{ handle }}.antlers.html'
                    ],
                    [
                        'type' => 'copy',
                        'input' => 'clients_fieldset.yaml.stub',
                        'output' => 'resources/fieldsets/{{ handle }}.yaml'
                    ],
                    [
                        'type' => 'update_page_builder',
                        'block' => [
                            'name' => '{{ name }}',
                            'instructions' => 'A {{ name }} logo cloud.',
                            'icon' => 'favorite-award',
                            'handle' => '{{ handle }}',
                        ]
                    ],
                    [
                        'type' => 'update_role',
                        'role' => 'editor',
                        'permissions' => ['view {{ handle }} entries', 'edit {{ handle }} entries', 'create {{ handle }} entries', 'delete {{ handle }} entries', 'publish {{ handle }} entries', 'reorder {{ handle }} entries', 'edit other authors {{ handle }} entries', 'publish other authors {{ handle }} entries', 'delete other authors {{ handle }} entries']
                    ],
                    [
                        'type' => 'notify',
                        'content' => "Add this to your `config/statamic/cp.php` widgets array:\n\n[\n\t'type' => 'collection',\n\t'collection' => '{{ handle }}',\n\t'width' => 50\n],"
                    ]
                ]
            ],
            [
                'handle' => 'events',
                'name' => 'Events',
                'description' => 'A dated renamable events collection.',
                'operations' => [
                    [
                        'type' => 'rename'
                    ],
                    [
                        'type' => 'copy',
                        'input' => 'events_blueprint.yaml.stub',
                        'output' => 'resources/blueprints/collections/{{ handle }}/{{ handle }}.yaml'
                    ],
                    [
                        'type' => 'copy',
                        'input' => 'events_collection.yaml.stub',
                        'output' => 'content/collections/{{ handle }}.yaml'
                    ],
                    [
                        'type' => 'copy',
                        'input' => 'events_fieldset.yaml.stub',
                        'output' => 'resources/fieldsets/{{ handle }}.yaml'
                    ],
                    [
                        'type' => 'copy',
                        'input' => 'events_item.antlers.html.stub',
                        'output' => 'resources/views/components/_{{ handle }}_item.antlers.html'
                    ],
                    [
                        'type' => 'copy',
                        'input' => 'events.antlers.html.stub',
                        'output' => 'resources/views/page_builder/_{{ handle }}.antlers.html'
                    ],
                    [
                        'type' => 'copy',
                        'input' => 'events.md.stub',
                        'output' => 'content/collections/pages/{{ multisite_handle }}/{{ handle }}.md'
                    ],
                    [
                        'type' => 'copy',
                        'input' => 'index_content.antlers.html.stub',
                        'output' => 'resources/views/page_builder/_index_content.antlers.html',
                        'skippable' => true
                    ],
                    [
                        'type' => 'copy',
                        'input' => 'index_content.yaml.stub',
                        'output' => 'resources/fieldsets/index_content.yaml',
                        'skippable' => true
                    ],
                    [
                        'type' => 'copy',
                        'input' => 'index.antlers.html.stub',
                        'output' => 'resources/views/{{ handle }}/index.antlers.html'
                    ],
                    [
                        'type' => 'copy',
                        'input' => 'show.antlers.html.stub',
                        'output' => 'resources/views/{{ handle }}/show.antlers.html'
                    ],
                    [
                        'type' => 'update_page_builder',
                        'block' => [
                            'name' => 'Index content',
                            'instructions' => 'Render the currently mounted entries if available.',
                            'icon' => 'file-content-list',
                            'handle' => 'index_content',
                        ]
                    ],
                    [
                        'type' => 'update_page_builder',
                        'block' => [
                            'name' => '{{ name }}',
                            'instructions' => 'List upcoming {{ name }}.',
                            'icon' => 'calendar-date',
                            'handle' => '{{ handle }}',
                        ]
                    ],
                    [
                        'type' => 'update_role',
                        'role' => 'editor',
                        'permissions' => ['view {{ handle }} entries', 'edit {{ handle }} entries', 'create {{ handle }} entries', 'delete {{ handle }} entries', 'publish {{ handle }} entries', 'reorder {{ handle }} entries', 'edit other authors {{ handle }} entries', 'publish other authors {{ handle }} entries', 'delete other authors {{ handle }} entries']
                    ],
                    [
                        'type' => 'notify',
                        'content' => "Add this to your `lang/locale/strings.php` file:\n\n// {{ name }}\n'{{ handle }}_all' => 'All {{ name }}',\n'{{ handle }}_date' => 'Date',\n'{{ handle }}_date_start' => 'Start date',\n'{{ handle }}_date_end' => 'End date',\n'{{ handle }}_more' => 'More {{ name }}',\n'{{ handle }}_when' => 'When',\n'{{ handle }}_where' => 'Where',\n'{{ handle }}_organizer' => 'Organizer',\n'{{ handle }}_tickets' => 'Tickets',"
                    ],
                    [
                        'type' => 'notify',
                        'content' => "Add this to your `config/statamic/cp.php` widgets array:\n\n[\n\t'type' => 'collection',\n\t'collection' => '{{ handle }}',\n\t'width' => 50\n],"
                    ]
                ]
            ],
            [
                'handle' => 'faq',
                'name' => 'FAQ',
                'description' => 'A FAQ collection.',
                'operations' => [
                    [
                        'type' => 'copy',
                        'input' => 'faq_blueprint.yaml.stub',
                        'output' => 'resources/blueprints/collections/faq/faq.yaml'
                    ],
                    [
                        'type' => 'copy',
                        'input' => 'faq_collection.yaml.stub',
                        'output' => 'content/collections/faq.yaml'
                    ],
                    [
                        'type' => 'copy',
                        'input' => 'faq_fieldset.yaml.stub',
                        'output' => 'resources/fieldsets/faq.yaml'
                    ],
                    [
                        'type' => 'copy',
                        'input' => 'faq.antlers.html.stub',
                        'output' => 'resources/views/page_builder/_faq.antlers.html'
                    ],
                    [
                        'type' => 'update_page_builder',
                        'block' => [
                            'name' => 'FAQ',
                            'instructions' => 'List frequently asked questions in an accordion.',
                            'icon' => 'alert-help-question',
                            'handle' => 'faq',
                        ]
                    ],
                    [
                        'type' => 'update_role',
                        'role' => 'editor',
                        'permissions' => ['view faq entries', 'edit faq entries', 'create faq entries', 'delete faq entries', 'publish faq entries', 'reorder faq entries', 'edit other authors faq entries', 'publish other authors faq entries', 'delete other authors faq entries']
                    ],
                    [
                        'type' => 'notify',
                        'content' => "\nAdd this to your `config/statamic/cp.php` widgets array:\n\n[\n\t'type' => 'collection',\n\t'collection' => 'faq',\n\t'width' => 50\n],\n"
                    ]
                ],
            ],
            [
                'handle' => 'image_credits',
                'name' => 'Image credits',
                'description' => 'List images and their credits.',
                'operations' => [
                    [
                        'type' => 'copy',
                        'input' => 'image_credits.antlers.html.stub',
                        'output' => 'resources/views/page_builder/_image_credits.antlers.html'
                    ],
                    [
                        'type' => 'copy',
                        'input' => 'image_credits_fieldset.yaml.stub',
                        'output' => 'resources/fieldsets/image_credits.yaml'
                    ],
                    [
                        'type' => 'copy',
                        'input' => 'images_blueprint.yaml.stub',
                        'output' => 'resources/blueprints/assets/images.yaml'
                    ],
                    [
                        'type' => 'update_page_builder',
                        'block' => [
                            'name' => 'Image credits',
                            'instructions' => 'List images with their credits.',
                            'icon' => 'content-book-open',
                            'handle' => 'image_credits',
                        ]
                    ],
                ]
            ],
            [
                'handle' => 'language_picker',
                'name' => 'Language picker',
                'description' => 'A multisite language picker.',
                'operations' => [
                    [
                        'type' => 'copy',
                        'input' => 'language_picker.antlers.html.stub',
                        'output' => 'resources/views/navigation/_language_picker.antlers.html'
                    ],
                    [
                        'type' => 'notify',
                        'content' => "Add this to your `lang/locale/strings.php` file:\n\n// Language picker\n'language_open' => 'Open language picker. Current language is :current_language',\n'language_close' => 'Close language picker',"
                    ],
                    [
                        'type' => 'notify',
                        'content' => "Add `{{ partial:navigation/language_picker }}` as the last list item in the main ul in `resources/views/navigation/_main.antlers.html`."
                    ]
                ]
            ],
            [
                'handle' => 'modal',
                'name' => 'Modal',
                'description' => 'A re-usable modal.',
                'operations' => [
                    [
                        'type' => 'copy',
                        'input' => 'modal.antlers.html.stub',
                        'output' => 'resources/views/components/_modal.antlers.html'
                    ],
                    [
                        'type' => 'copy',
                        'input' => 'invoke_modal.antlers.html.stub',
                        'output' => 'resources/views/components/_invoke_modal.antlers.html'
                    ],
                    [
                        'type' => 'copy',
                        'input' => 'invoke_modal.yaml.stub',
                        'output' => 'resources/fieldsets/invoke_modal.yaml'
                    ],
                    [
                        'type' => 'update_article_sets',
                        'block' => [
                            'name' => 'Invoke modal',
                            'icon' => 'alert-warning-exclamation-mark',
                            'description' => 'Invokes a modal.',
                            'handle' => 'invoke_modal',
                        ]
                    ],
                    [
                        'type' => 'notify',
                        'content' => "Make sure to `{{ yield:modal }}` in your layout file before closing the `<body>`."
                    ]
                ]
            ],
            [
                'handle' => 'news',
                'name' => 'News',
                'description' => 'A dated renamable news/blog collection with feed.',
                'operations' => [
                    [
                        'type' => 'rename'
                    ],
                    [
                        'type' => 'copy',
                        'input' => 'feed.antlers.xml.stub',
                        'output' => 'resources/views/feed/feed.antlers.xml'
                    ],
                    [
                        'type' => 'copy',
                        'input' => 'index_content.antlers.html.stub',
                        'output' => 'resources/views/page_builder/_index_content.antlers.html',
                        'skippable' => true
                    ],
                    [
                        'type' => 'copy',
                        'input' => 'index_content.yaml.stub',
                        'output' => 'resources/fieldsets/index_content.yaml',
                        'skippable' => true
                    ],
                    [
                        'type' => 'copy',
                        'input' => 'index.antlers.html.stub',
                        'output' => 'resources/views/{{ handle }}/index.antlers.html'
                    ],
                    [
                        'type' => 'copy',
                        'input' => 'news_blueprint.yaml.stub',
                        'output' => 'resources/blueprints/collections/{{ handle }}/{{ handle }}.yaml'
                    ],
                    [
                        'type' => 'copy',
                        'input' => 'news_collection.yaml.stub',
                        'output' => 'content/collections/{{ handle }}.yaml'
                    ],
                    [
                        'type' => 'copy',
                        'input' => 'news_fieldset.yaml.stub',
                        'output' => 'resources/fieldsets/{{ handle }}.yaml'
                    ],
                    [
                        'type' => 'copy',
                        'input' => 'news_item.antlers.html.stub',
                        'output' => 'resources/views/components/_{{ handle }}_item.antlers.html'
                    ],
                    [
                        'type' => 'copy',
                        'input' => 'news.antlers.html.stub',
                        'output' => 'resources/views/page_builder/_{{ handle }}.antlers.html'
                    ],
                    [
                        'type' => 'copy',
                        'input' => 'news.md.stub',
                        'output' => 'content/collections/pages/{{ multisite_handle }}/{{ handle }}.md'
                    ],
                    [
                        'type' => 'copy',
                        'input' => 'show.antlers.html.stub',
                        'output' => 'resources/views/{{ handle }}/show.antlers.html'
                    ],
                    [
                        'type' => 'update_page_builder',
                        'block' => [
                            'name' => 'Index content',
                            'instructions' => 'Render the currently mounted entries if available.',
                            'icon' => 'file-content-list',
                            'handle' => 'index_content',
                        ]
                    ],
                    [
                        'type' => 'update_page_builder',
                        'block' => [
                            'name' => '{{ name }}',
                            'instructions' => 'List the most recent {{ name }}.',
                            'icon' => 'share-mega-phone',
                            'handle' => '{{ handle }}',
                        ]
                    ],
                    [
                        'type' => 'update_role',
                        'role' => 'editor',
                        'permissions' => ['view {{ handle }} entries', 'edit {{ handle }} entries', 'create {{ handle }} entries', 'delete {{ handle }} entries', 'publish {{ handle }} entries', 'reorder {{ handle }} entries', 'edit other authors {{ handle }} entries', 'publish other authors {{ handle }} entries', 'delete other authors {{ handle }} entries']
                    ],
                    [
                        'type' => 'notify',
                        'content' => "Add this to your `lang/locale/strings.php` file:\n\n// {{ name }}\n'{{ handle }}_all' => 'All articles',\n'{{ handle }}_more' => 'More articles',\n'{{ handle }}_read_more' => 'Read more',"
                    ],
                    [
                        'type' => 'notify',
                        'content' => "Add this to your `config/statamic/cp.php` widgets array:\n\n[\n\t'type' => 'collection',\n\t'collection' => '{{ handle }}',\n\t'width' => 50\n],"
                    ],
                    [
                        'type' => 'notify',
                        'content' => "Add this to your `routes/web.php` file:\n\n// The route to the RSS feed.\nRoute::statamic('/feed/{{ handle }}', 'feed/feed', [\n\t'layout' => null,\n\t'content_type' => 'application/xml',\n]);"
                    ],
                    [
                        'type' => 'notify',
                        'content' => "Add this to the `<head>` in your `views/layout.antlers.html` file:\n\n<link rel=\"alternate\" type=\"application/rss+xml\" title=\"{{ name }} Feed\" href=\"{{ config:app:url }}/feed/{{ handle }}\"/>"
                    ],
                    [
                        'type' => 'notify',
                        'content' => "Add this to the `exclude` array in your `config/statamic/static_caching.php` file:\n\n'/feed*',"
                    ]
                ]
            ],
            [
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
                        'content' => "When you want to group your pricing related content, also add to or create and add to `resources/preferences.yaml`:\nnav:\n\040content:\n\040\040reorder: true\n\040\040items:\n\040\040\040'content::collections': '@inherit'\n\040\040\040'content::pricing':\n\040\040\040\040action: '@modify'\n\040\040\040\040url: collections/tiers\n\040\040\040\040children:\n\040\040\040\040\040'content::collections::tiers': '@move'\n\040\040\040\040\040'content::collections::features': '@move'\n\040\040\040\040\040'content::taxonomies::groups': '@move'"
                    ],
                ]
            ],
            [
                'handle' => 'read_more',
                'name' => 'Read more',
                'description' => 'A read more set in Bard.',
                'operations' => [
                    [
                        'type' => 'copy',
                        'input' => 'resources/fieldsets/read_more.yaml.stub',
                        'output' => 'resources/fieldsets/read_more.yaml'
                    ],
                    [
                        'type' => 'copy',
                        'input' => 'resources/views/components/_read_more.antlers.html.stub',
                        'output' => 'resources/views/components/_read_more.antlers.html'
                    ],
                    [
                        'type' => 'update_article_sets',
                        'block' => [
                            'name' => 'Read more',
                            'icon' => 'content-book-open',
                            'description' => 'Link to a related article.',
                            'handle' => 'read_more',
                        ]
                    ],
                    [
                        'type' => 'notify',
                        'content' => "Add this to your `lang/locale/strings.php` file:\n\n'read_more' => 'Read more',"
                    ],
                ]
            ],
            [
                'handle' => 'search',
                'name' => 'Search',
                'description' => 'Search form search results.',
                'operations' => [
                    [
                        'type' => 'copy',
                        'input' => 'search.antlers.html.stub',
                        'output' => 'resources/views/search.antlers.html'
                    ],
                    [
                        'type' => 'copy',
                        'input' => 'search_form.antlers.html.stub',
                        'output' => 'resources/views/components/_search_form.antlers.html'
                    ],
                    [
                        'type' => 'notify',
                        'content' => "Add this to your `lang/locale/strings.php` file:\n\n// Search\n'search' => 'Search',\n'search_close' => 'Close search',\n'search_open' => 'Open search',\n'search_no_results' => 'No results found',\n'search_results_for' => 'Results for',"
                    ],
                    [
                        'type' => 'notify',
                        'content' => "To enable this do the following:\n1. Add `{{ partial:components/search_form }}` as the last list item in the main ul in `resources/views/navigation/_main.antlers.html`.\n2. Add the following route to `routes/web.php`:\n\n// The Search route to display search results with `views/search.antlers.html`.\nRoute::statamic('/search', 'search');\n\n3. Add fields you want indexed to the index in config/statamic/search.php. The page_builder field is added by default.\n4. Update the search index by running php please search:update --all.\n5. Make sure you add the update command to your deployment script."
                    ]
                ]
            ],
            [
                'handle' => 'team_members',
                'name' => 'Team members',
                'description' => 'A renamable team member collection.',
                'operations' => [
                    [
                        'type' => 'rename'
                    ],
                    [
                        'type' => 'copy',
                        'input' => 'team_members_blueprint.yaml.stub',
                        'output' => 'resources/blueprints/collections/{{ handle }}/{{ handle }}.yaml'
                    ],
                    [
                        'type' => 'copy',
                        'input' => 'team_members_collection.yaml.stub',
                        'output' => 'content/collections/{{ handle }}.yaml'
                    ],
                    [
                        'type' => 'copy',
                        'input' => 'team_members.antlers.html.stub',
                        'output' => 'resources/views/page_builder/_{{ handle }}.antlers.html'
                    ],
                    [
                        'type' => 'copy',
                        'input' => 'team_members_fieldset.yaml.stub',
                        'output' => 'resources/fieldsets/{{ handle }}.yaml'
                    ],
                    [
                        'type' => 'update_page_builder',
                        'block' => [
                            'name' => '{{ name }}',
                            'instructions' => 'List {{ name }}.',
                            'icon' => 'user-multiple',
                            'handle' => '{{ handle }}',
                        ]
                    ],
                    [
                        'type' => 'update_role',
                        'role' => 'editor',
                        'permissions' => ['view {{ handle }} entries', 'edit {{ handle }} entries', 'create {{ handle }} entries', 'delete {{ handle }} entries', 'publish {{ handle }} entries', 'reorder {{ handle }} entries', 'edit other authors {{ handle }} entries', 'publish other authors {{ handle }} entries', 'delete other authors {{ handle }} entries']
                    ],
                    [
                        'type' => 'notify',
                        'content' => "Add this to your `config/statamic/cp.php` widgets array:\n\n[\n\t'type' => 'collection',\n\t'collection' => '{{ handle }}',\n\t'width' => 50\n],"
                    ]
                ]
            ],
            [
                'handle' => 'theme_toggle',
                'name' => 'Theme toggle',
                'description' => 'Theme toggle for dark mode.',
                'operations' => [
                    [
                        'type' => 'copy',
                        'input' => 'theme_toggle.antlers.html.stub',
                        'output' => 'resources/views/components/_theme_toggle.antlers.html'
                    ],
                    [
                        'type' => 'notify',
                        'content' => "Add this to your `lang/locale/strings.php` file:\n\n// Theme toggle\n'theme_toggle_dark' => 'Use dark theme.',\n'theme_toggle_light' => 'Use light theme.',\n'theme_toggle_system' => 'Use system preference.',\n'theme_toggle_dark_short' => 'Dark',\n'theme_toggle_light_short' => 'Light',\n'theme_toggle_system_short' => 'System',\n'theme_toggle_toggle_open' => 'Open dark mode picker.',\n'theme_toggle_toggle_close' => 'Close dark mode picker.',"
                    ],
                    [
                        'type' => 'notify',
                        'content' => "To enable this do the following:\n1. Add `darkMode: 'class'` to `tailwind.config.js`.\n2. Add `{{ partial:components/theme_toggle }}` as the last list item in the main ul in `resources/views/navigation/_main_desktop.antlers.html`. The `section:theme_toggle` is automatically yielded in `resources/views/snippets/_browser_appearance.antlers.html`."
                    ]
                ]
            ],
            [
                'handle' => 'vacancies',
                'name' => 'Vacancies',
                'description' => 'A dated renamable vacancies collection.',
                'operations' => [
                    [
                        'type' => 'rename'
                    ],
                    [
                        'type' => 'copy',
                        'input' => 'index.antlers.html.stub',
                        'output' => 'resources/views/{{ handle }}/index.antlers.html'
                    ],
                    [
                        'type' => 'copy',
                        'input' => 'index_content.antlers.html.stub',
                        'output' => 'resources/views/page_builder/_index_content.antlers.html',
                        'skippable' => true
                    ],
                    [
                        'type' => 'copy',
                        'input' => 'index_content.yaml.stub',
                        'output' => 'resources/fieldsets/index_content.yaml',
                        'skippable' => true
                    ],
                    [
                        'type' => 'copy',
                        'input' => 'show.antlers.html.stub',
                        'output' => 'resources/views/{{ handle }}/show.antlers.html'
                    ],
                    [
                        'type' => 'copy',
                        'input' => 'vacancies.md.stub',
                        'output' => 'content/collections/pages/{{ multisite_handle }}/{{ handle }}.md'
                    ],
                    [
                        'type' => 'copy',
                        'input' => 'vacancies_item.antlers.html.stub',
                        'output' => 'resources/views/components/_{{ handle }}_item.antlers.html'
                    ],
                    [
                        'type' => 'copy',
                        'input' => 'vacancies_blueprint.yaml.stub',
                        'output' => 'resources/blueprints/collections/{{ handle }}/{{ handle }}.yaml'
                    ],
                    [
                        'type' => 'copy',
                        'input' => 'vacancies_collection.yaml.stub',
                        'output' => 'content/collections/{{ handle }}.yaml'
                    ],
                    [
                        'type' => 'update_page_builder',
                        'block' => [
                            'name' => 'Index content',
                            'instructions' => 'Render the currently mounted entries if available.',
                            'icon' => 'file-content-list',
                            'handle' => 'index_content',
                        ]
                    ],
                    [
                        'type' => 'update_role',
                        'role' => 'editor',
                        'permissions' => ['view {{ handle }} entries', 'edit {{ handle }} entries', 'create {{ handle }} entries', 'delete {{ handle }} entries', 'publish {{ handle }} entries', 'reorder {{ handle }} entries', 'edit other authors {{ handle }} entries', 'publish other authors {{ handle }} entries', 'delete other authors {{ handle }} entries']
                    ],
                    [
                        'type' => 'notify',
                        'content' => "Add this to your `lang/locale/strings.php` file:\n\n// {{ name }}\n'{{ handle }}_expires' => 'Expires',\n'{{ handle }}_hours' => 'Hours',\n'{{ handle }}_published' => 'Published',\n'{{ handle }}_region' => 'Region',\n'{{ handle }}_employment_type' => 'Employment type',\n'{{ handle }}_part_time' => 'Part time',\n'{{ handle }}_full_time' => 'Full time',\n'{{ handle }}_salary_min' => 'Minimum salary',\n'{{ handle }}_salary_max' => 'Maximum salary',"
                    ],
                    [
                        'type' => 'notify',
                        'content' => "Add this to your `config/statamic/cp.php` widgets array:\n\n[\n\t'type' => 'collection',\n\t'collection' => '{{ handle }}',\n\t'width' => 50\n],"
                    ],
                ]
            ],
        ]);
    }
}
