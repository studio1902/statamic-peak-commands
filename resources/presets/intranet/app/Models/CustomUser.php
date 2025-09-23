<?php

namespace App\Models;

use Illuminate\Auth\MustVerifyEmail as MustVerifyEmailTrait;
use Illuminate\Contracts\Auth\MustVerifyEmail;

class CustomUser extends \Statamic\Auth\File\User implements MustVerifyEmail
{
    use MustVerifyEmailTrait;

    public function markEmailAsVerified()
    {
        $this->email_verified_at = now();

        return $this->save();
    }
}
