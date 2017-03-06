<?php

namespace Sportlery\Library\Components;

use Cms\Classes\ComponentBase;
use Sportlery\Library\Classes\FriendshipStatus;
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
        $this->page['profile'] = User::findByHashId($this->param('id'));
        $this->page['profileHashId'] = $this->param('id');

        $friendshipStatus = \Auth::getUser()->getFriendshipStatus($this->page['profile']);
        $this->page['friendshipNone'] = is_null($friendshipStatus);
        $this->page['blocked'] = $friendshipStatus === FriendshipStatus::BLOCKED;
        $this->page['isFriend'] = $friendshipStatus === FriendshipStatus::ACCEPTED;
        $this->page['friendshipPending'] = $friendshipStatus === FriendshipStatus::PENDING;
        $this->page['friendshipDeclined'] = $friendshipStatus === FriendshipStatus::DECLINED;
    }
}
