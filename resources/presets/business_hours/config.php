<?php

return [
    'handle' => 'business_hours',
    'name' => 'Business hours',
    'description' => 'Configure and list business hours, live open/not open component',
    'operations' => [
        [
            'type' => 'copy',
            'input' => 'resources/blueprints/globals/business_hours.yaml',
            'output' => 'resources/blueprints/globals/business_hours.yaml'
        ],
        [
            'type' => 'copy',
            'input' => 'content/globals/business_hours.yaml',
            'output' => 'content/globals/business_hours.yaml'
        ],
        [
            'type' => 'copy',
            'input' => 'resources/views/snippets/_business_hours.antlers.html',
            'output' => 'resources/views/snippets/_business_hours.antlers.html'
        ],
        [
            'type' => 'copy',
            'input' => 'resources/views/components/_business_hours.antlers.html',
            'output' => 'resources/views/components/_business_hours.antlers.html'
        ],
        [
            'type' => 'copy',
            'input' => 'resources/views/components/_call_now.antlers.html',
            'output' => 'resources/views/components/_call_now.antlers.html'
        ],
        [
            'type' => 'copy',
            'input' => 'resourcces/svg/phone.svg',
            'output' => 'resources/svg/phone.svg'
        ],
        [
            'type' => 'notify',
            'content' => "Make sure to set the correct timezone in `config/app.php`."
        ],
        [
            'type' => 'notify',
            'content' => "Import the following partials where needed: `{{ partial:components/business_hours }}` and `{{ partial:components/call_now }}`."
        ],
        [
            'type' => 'notify',
            'content' => "Add this to your `lang/locale/strings.php` file:\n\n// Business hours\n'business_hours_can_reach' => 'Available now',\n'business_hours_cant_reach' => 'Not available now',\n'business_hours_closed' => 'Closed',\n'business_hours_open_now' => 'Open now',\n'business_hours_closed_now' => 'Closed now',\n'business_hours_monday' => 'Monday',\n'business_hours_tuesday' => 'Tuesday',\n'business_hours_wednesday' => 'Wednesday',\n'business_hours_thursday' => 'Thursday',\n'business_hours_friday' => 'Friday',\n'business_hours_saturday' => 'Saturday',\n'business_hours_sunday' => 'Sunday',"
        ]
    ]
];
