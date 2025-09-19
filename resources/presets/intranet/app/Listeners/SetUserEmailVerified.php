<?php

namespace App\Listeners;

use Statamic\Events\UserCreated;
use Statamic\Statamic;

class SetUserEmailVerified
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\UserRegistered  $event
     */
    public function handle(UserCreated $event): void
    {
        $user = $event->user;
        if (Statamic::isCpRoute() && ! $user->email_verified_at) {
            $user->set('email_verified_at', now())->save();
        }
    }
}
