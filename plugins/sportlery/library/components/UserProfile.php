<?php

namespace Sportlery\Library\Components;

use Cms\Classes\ComponentBase;
use Sportlery\Library\Models\User;

class UserProfile extends ComponentBase
{
    /**
     * Returns information about this component, including name and description.
     */
    public function componentDetails()
    {
        return [
            'name' => 'User Profile',
            'description' => 'Display a User profile',
        ];
    }

    public function onRun()
    {
        $this->page['user'] = User::findByHashId($this->param('id'));
    }
}
