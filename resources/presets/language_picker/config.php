<?php

return [
    'handle' => 'language_picker',
    'name' => 'Language picker',
    'description' => 'A multisite language picker.',
    'operations' => [
        [
            'type' => 'copy',
            'input' => 'resources/views/navigation/_language_picker.antlers.html',
            'output' => 'resources/views/navigation/_language_picker.antlers.html'
        ],
        [
            'type' => 'notify',
            'content' => "Add `{{ partial:navigation/language_picker }}` as the last list item in the main ul in `resources/views/navigation/_main.antlers.html`."
        ],
        [
            'type' => 'notify',
            'content' => "Add this to your `lang/locale/strings.php` file:\n\n// Language picker\n'language_open' => 'Open language picker. Current language is :current_language',\n'language_close' => 'Close language picker',"
        ]
    ]
];
