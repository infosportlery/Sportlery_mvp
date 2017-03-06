<?php

namespace Sportlery\Library\Classes;

use Sportlery\Library\Models\User;

class AuthManager extends \RainLab\User\Classes\AuthManager
{
    protected $userModel = User::class;
}
