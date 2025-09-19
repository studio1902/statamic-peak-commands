<?php

namespace App\Listeners;

use Statamic\Events\UserRegistered;

class SendEmailVerificationNotification
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
     * @param  object  $event
     */
    public function handle(UserRegistered $event): void
    {
        $event->user->model()->sendEmailVerificationNotification();
    }
}
