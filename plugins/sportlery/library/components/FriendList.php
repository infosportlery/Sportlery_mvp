<?php

namespace Sportlery\Library\Components;

use Cms\Classes\ComponentBase;

class FriendList extends ComponentBase
{
    /**
     * Returns information about this component, including name and description.
     */
    public function componentDetails()
    {
        return [
            'name' => 'Friends list',
            'description' => 'Display a list of the current users friends',
        ];
    }

    public function onRun()
    {
        $user = \Auth::getUser();

        $this->page['friends'] = $user->listFriends();
    }
}
