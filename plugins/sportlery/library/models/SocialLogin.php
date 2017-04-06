<?php

namespace Sportlery\Library\Models;

use October\Rain\Database\Model;
use RainLab\User\Models\User;

class SocialLogin extends Model
{
    /**
     * @var string The database table used by the model.
     */
    public $table = 'spr_social_logins';

    public $belongsTo = [
        'user' => User::class,
    ];
}
