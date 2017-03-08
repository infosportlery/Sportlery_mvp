<?php namespace Sportlery\Library\Models;

use RainLab\User\Models\User as RainlabUser;

class User extends RainlabUser {
	
    use \Sportlery\Library\Classes\Traits\HashIds;



}