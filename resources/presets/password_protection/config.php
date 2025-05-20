<?php

return [
    'handle' => 'password_protection',
    'name' => 'Password protection',
    'description' => 'Protect pages with a password.',
    'operations' => [
        [
            'type' => 'copy',
            'input' => 'resources/views/page_builder/_login.antlers.html',
            'output' => 'resources/views/page_builder/_login.antlers.html',
        ],
        [
            'type' => 'copy',
            'input' => 'content/collections/pages/login.md',
            'output' => 'content/collections/pages/login.md',
        ],
        [
            'type' => 'copy',
            'input' => 'resources/fieldsets/login.yaml',
            'output' => 'resources/fieldsets/login.yaml',
        ],
        [
            'type' => 'copy',
            'input' => 'resources/fieldsets/protect.yaml',
            'output' => 'resources/fieldsets/protect.yaml',
        ],
        [
            'type' => 'update_page_builder',
            'block' => [
                'name' => 'Login',
                'instructions' => 'The protection login form.',
                'icon' => 'login-key-2',
                'handle' => 'login',
            ],
        ],
        [
            'type' => 'notify',
            'content' => "Add this to your `routes/web.php` file:\n\nuse Illuminate\Http\Request;\nuse Illuminate\Support\Facades\Route;\nuse Statamic\Facades\GlobalSet;\nuse Statamic\Facades\Entry;\n\nRoute::get('/protection-form-url', function (Request \$request) {\n\treturn redirect(Entry::find(GlobalSet::findByHandle('configuration')->inDefaultSite()->get('login_entry'))->url() . '?token=' . \$request->token);\n});",
        ],
        [
            'type' => 'notify',
            'content' => "Update the `password` array in `config/statamic/protect.php` with the following:\n\n'password' => [\n\t'driver' => 'password',\n\t'allowed' => [],\n\t'field' => 'password',\n\t'form_url' => 'protection-form-url',\n],",
        ],
        [
            'type' => 'notify',
            'content' => "Add the following field to a global like `resources/blueprints/globals/configruation.yaml`:\n\n-\n\thandle: login_entry\n\tfield: common.entry\n\tconfig:\n\t\tdisplay: Login\n\t\twidth: 50\n\t\tinstructions: 'The login page.'\n\t\tcollections:\n\t\t\t- pages\n\t\tvalidate:\n\t\t\t- required",
        ],
        [
            'type' => 'notify',
            'content' => "Add the following section to the blueprints you want to potect, like `resources/blueprints/collections/pages/page.yaml`:\n\n-\n\tdisplay: 'Protect'\n\tfields:\n\t\t-\n\t\t\timport: protect",
        ],
        [
            'type' => 'notify',
            'content' => "Add this to your `lang/locale/strings.php` file:\n\n'password' => 'Password',\n'password_invalid' => 'Password invalid.',\n'password_login' => 'Login',",
        ],
    ],
];
