<?php

namespace App\Http\Controllers;

use Statamic\Http\Controllers\UserController as StatamicUserController;

class UserController extends StatamicUserController
{
    public function __construct()
    {
        $this->middleware('throttle:6,1')->only('register');
    }
}
