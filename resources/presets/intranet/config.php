<?php

return [
    'handle' => 'intranet',
    'name' => 'Intranet',
    'description' => 'Intranet with frontend users and email verification.',
    'operations' => [
        [
            'type' => 'copy',
            'input' => 'app/http/Controllers/UserController.php',
            'output' => 'app/http/Controllers/UserController.php',
        ],
        [
            'type' => 'copy',
            'input' => 'app/Listeners/SendEmailVerificationNotification.php',
            'output' => 'app/Listeners/SendEmailVerificationNotification.php',
        ],
        [
            'type' => 'copy',
            'input' => 'app/models/CustomUser.php',
            'output' => 'app/models/CustomUser.php',
        ],
        [
            'type' => 'copy',
            'input' => 'app/Listeners/SetUserEmailVerified.php',
            'output' => 'app/Listeners/SetUserEmailVerified.php',
        ],
        [
            'type' => 'copy',
            'input' => 'app/Statamic/CustomUrlExcluder.php',
            'output' => 'app/Statamic/CustomUrlExcluder.php',
        ],
        [
            'type' => 'copy',
            'input' => 'resources/blueprints/user.yaml',
            'output' => 'resources/blueprints/user.yaml',
        ],
        [
            'type' => 'copy',
            'input' => 'resources/blueprints/globals/intranet.yaml',
            'output' => 'resources/blueprints/globals/intranet.yaml',
        ],
        [
            'type' => 'copy',
            'input' => 'content/globals/intranet.yaml',
            'output' => 'content/globals/intranet.yaml',
        ],
        [
            'type' => 'copy',
            'input' => 'resources/fieldsets/account.yaml',
            'output' => 'resources/fieldsets/account.yaml',
        ],
        [
            'type' => 'copy',
            'input' => 'resources/fieldsets/email_verification.yaml',
            'output' => 'resources/fieldsets/email_verification.yaml',
        ],
        [
            'type' => 'copy',
            'input' => 'resources/fieldsets/login.yaml',
            'output' => 'resources/fieldsets/login.yaml',
        ],
        [
            'type' => 'copy',
            'input' => 'resources/fieldsets/password_forgotten.yaml',
            'output' => 'resources/fieldsets/password_forgotten.yaml',
        ],
        [
            'type' => 'copy',
            'input' => 'resources/fieldsets/password_reset.yaml',
            'output' => 'resources/fieldsets/password_reset.yaml',
        ],
        [
            'type' => 'copy',
            'input' => 'resources/fieldsets/register.yaml',
            'output' => 'resources/fieldsets/register.yaml',
        ],
        [
            'type' => 'copy',
            'input' => 'resources/views/components/_form_input.antlers.html',
            'output' => 'resources/views/components/_form_input.antlers.html',
        ],
        [
            'type' => 'copy',
            'input' => 'resources/views/components/_login.antlers.html',
            'output' => 'resources/views/components/_login.antlers.html',
        ],
        [
            'type' => 'copy',
            'input' => 'resources/views/page_builder/_account.antlers.html',
            'output' => 'resources/views/page_builder/_account.antlers.html',
        ],
        [
            'type' => 'copy',
            'input' => 'resources/views/page_builder/_email_verification.antlers.html',
            'output' => 'resources/views/page_builder/_email_verification.antlers.html',
        ],
        [
            'type' => 'copy',
            'input' => 'resources/views/page_builder/_login.antlers.html',
            'output' => 'resources/views/page_builder/_login.antlers.html',
        ],
        [
            'type' => 'copy',
            'input' => 'resources/views/page_builder/_password_forgotten.antlers.html',
            'output' => 'resources/views/page_builder/_password_forgotten.antlers.html',
        ],
        [
            'type' => 'copy',
            'input' => 'resources/views/page_builder/_password_reset.antlers.html',
            'output' => 'resources/views/page_builder/_password_reset.antlers.html',
        ],
        [
            'type' => 'copy',
            'input' => 'resources/views/page_builder/_register.antlers.html',
            'output' => 'resources/views/page_builder/_register.antlers.html',
        ],
        [
            'type' => 'copy',
            'input' => 'resources/views/vendor/mail/html/message.blade.php',
            'output' => 'resources/views/vendor/mail/html/message.blade.php',
        ],
        [
            'type' => 'copy',
            'input' => 'content/collections/pages/account.md',
            'output' => 'content/collections/pages/account.md',
        ],
        [
            'type' => 'copy',
            'input' => 'content/collections/pages/email-verification.md',
            'output' => 'content/collections/pages/email-verification.md',
        ],
        [
            'type' => 'copy',
            'input' => 'content/collections/pages/login.md',
            'output' => 'content/collections/pages/login.md',
        ],
        [
            'type' => 'copy',
            'input' => 'content/collections/pages/password-forgotten.md',
            'output' => 'content/collections/pages/password-forgotten.md',
        ],
        [
            'type' => 'copy',
            'input' => 'content/collections/pages/password-reset.md',
            'output' => 'content/collections/pages/password-reset.md',
        ],
        [
            'type' => 'copy',
            'input' => 'content/collections/pages/register.md',
            'output' => 'content/collections/pages/register.md',
        ],
        [
            'type' => 'notify',
            'content' => "To install the following page builder blocks, optionally create a new group named `Account` when prompted. Use the description `Account based frontend user blocks.`. and set the icon to `user-multiple`.",
        ],
        [
            'type' => 'update_page_builder',
            'block' => [
                'name' => 'Account',
                'instructions' => 'Edit account detailis',
                'icon' => 'user-avatar',
                'handle' => 'account',
            ],
        ],
        [
            'type' => 'update_page_builder',
            'block' => [
                'name' => 'Email verification',
                'instructions' => 'Email verification page.',
                'icon' => 'social-mail-send-email-message',
                'handle' => 'email_verification',
            ],
        ],
        [
            'type' => 'update_page_builder',
            'block' => [
                'name' => 'Login',
                'instructions' => 'The login form.',
                'icon' => 'user-security-lock',
                'handle' => 'login',
            ],
        ],
        [
            'type' => 'update_page_builder',
            'block' => [
                'name' => 'Password forgotten',
                'instructions' => 'The password forgotten form.',
                'icon' => 'security-lock',
                'handle' => 'password_forgotten',
            ],
        ],
        [
            'type' => 'update_page_builder',
            'block' => [
                'name' => 'Password reset',
                'instructions' => 'The password reset form.',
                'icon' => 'security-unlock',
                'handle' => 'password_reset',
            ],
        ],
        [
            'type' => 'update_page_builder',
            'block' => [
                'name' => 'Register',
                'instructions' => 'The register form.',
                'icon' => 'login-key-2',
                'handle' => 'register',
            ],
        ],
        [
            'type' => 'update_role',
            'role' => 'editor',
            'permissions' => ['edit intranet globals'],
        ],
        [
            'type' => 'notify',
            'content' => "Add this to the `boot()` method in your `AppServiceProvider.php`.:\n\n\$this->app->bind(\Statamic\Http\Controllers\UserController::class, \App\Http\Controllers\UserController::class);",
        ],
        [
            'type' => 'notify',
            'content' => "Add this to the `boot()` method in your `AppServiceProvider.php`.:\n\n\$this->app->bind(\Statamic\Contracts\Auth\User::class, \App\Models\CustomUser::class);",
        ],
        [
            'type' => 'notify',
            'content' => "Add this to the `register()` method in your `AppServiceProvider.php`.:\n\n\$this->app->bind(\App\Statamic\CustomUrlExcluder::class, function (\$app) {\n\treturn new \App\Statamic\CustomUrlExcluder (\n\t\t\$app[\Statamic\StaticCaching\Cacher::class]->getBaseUrl(),\n\t\t\$app['config']['statamic.static_caching.exclude.urls'] ?? []\n\t);\n});",
        ],
        [
            'type' => 'notify',
            'content' => "Add this to your exclude array in `config/statamic/static_caching.php`:\n\n'class' => \App\Statamic\CustomUrlExcluder::class,",
        ],
        [
            'type' => 'notify',
            'content' => "Add this as your `registration_form_honeypot_field` value in `config/statamic/users.php`:\n\n'registration_form_honeypot_field' => 'fax',",
        ],
        [
            'type' => 'notify',
            'content' => "Add this to your navigation or where an account/login button makes sense:\n\n{{ partial:components/login }}",
        ],
        [
            'type' => 'notify',
            'content' => "Add the following to your routes file in `web.php`:\n\nuse Statamic\Facades\User;\nuse Illuminate\Auth\Events\Verified;\nuse Illuminate\Http\Request;\nuse Illuminate\Support\Facades\Auth;\nuse Illuminate\Support\Facades\Route;\nuse Statamic\Facades\Entry;\nuse Statamic\Globals\GlobalSet;\nuse Statamic\Http\Middleware\Localize;\n\n// This is the URL that the email verifcation link points to.\nRoute::get('/email-verification/{id}/{hash}', function (Request \$request) {\n\t\$user = User::find(\$request->route('id'));\n\n\tif (! hash_equals((string) \$user->getKey(), (string) \$request->route('id'))) {\n\t\tabort(403);\n\t}\n\n\tif (! hash_equals(sha1(\$user->getEmailForVerification()), (string) \$request->route('hash'))) {\n\t\tabort(403);\n\t}\n\n\tAuth::login(\$user);\n\n\tif (! \$user->hasVerifiedEmail()) {\n\t\t\$user->markEmailAsVerified();\n\n\tevent(new Verified(\$user));\n\t}\n\n\t\$accountEntry = Entry::find(GlobalSet::findByHandle('intranet')\n\t\t->inDefaultSite()\n\t\t->get('account_entry'));\n\n\treturn redirect(\$accountEntry->url());\n\t})->middleware(['signed'])->name('verification.verify');\n\n// This route is used to resend the email verification link.\nRoute::post('/email-verification/notificatie', function (Request \$request) {\n\t\$request->user()->sendEmailVerificationNotification();\n\n\treturn back()->with('success', 'Verification link sent.');\n})->middleware(['auth', 'throttle:6,1', Localize::class])->name('verification.send');",
        ],
        [
            'type' => 'notify',
            'content' => "Add this to the blueprints where you want to be able to require users to login:\n\n-\n\tdisplay: Intranet\n\tfields:\n\t\t-\n\t\t\thandle: requires_login\n\t\t\tfield:\n\t\t\t\ttype: toggle\n\t\t\t\tdisplay: 'Requires login'\n\t\t\t\tinstructions_position: below",
        ],
        [
            'type' => 'notify',
            'content' => "Add this to the templates where you want to be able to require users to login:\n\n{{ if requires_login }}\n\t{{ user }}\n\t\t{{ if !email_verified_at }}\n\t\t\t{{ redirect to=\"{ intranet:login_entry | url }?redirect={{ uri }}\" }}\n\t\t{{ /if }}\n\t{{ /user }}\n{{ /if }}",
        ],
        [
            'type' => 'notify',
            'content' => "Add this to your `lang/locale/strings.php` file:\n(and possibly any other locales you support)\n\n'account' => 'Account',\n'account_cancel' => 'Cancel',\n'account_current_password' => 'Current password',\n'account_email' => 'Email',\n'account_first_name' => 'First name',\n'account_last_name' => 'Last name',\n'account_login' => 'Log in',\n'account_logout' => 'Log out',\n'account_new_password' => 'New password',\n'account_new_password_confirmation' => 'Confirm new password',\n'account_password' => 'Password',\n'account_password_confirmation' => 'Confirm password',\n'account_password_forgotten' => 'Forgot your password?',\n'account_password_reset' => 'Reset password',\n'account_password_reset_request' => 'Send email',\n'account_password_save' => 'Change password',\n'account_register' => 'Register',\n'account_reset_invalid' => 'This password reset url is invalid.',\n'account_save' => 'Save changes',\n'account_verification_resend' => 'Resend email',\n'account_verification_resend_succes' => 'The email has been resend.',",
        ],
    ],
];
