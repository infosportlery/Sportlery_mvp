<?php

namespace Sportlery\Library\Components;

use Auth;
use Cms\Classes\ComponentBase;
use Redirect;
use RainLab\User\Models\User;
use Sportlery\Library\Classes\FriendshipStatus;

class UserFriendList extends ComponentBase
{
    /**
     * Returns information about this component, including name and description.
     */
    public function componentDetails()
    {
        return [
            'name' => 'User Friends list',
            'description' => 'Display a list of the current users friends',
        ];
    }

    public function defineProperties()
    {
        return [
            'listType' => [
                'title'       => 'List type',
                'description' => 'The type of friends list to show',
                'type'        => 'dropdown',
                'default'     => 'friends',
                'options'     => [
                    'blocked'  => 'Blocked users',
                    'friends'  => 'All accepted friends',
                    'sent'     => 'All sent friend requests',
                    'received' => 'All received friend requests',
                ]
            ],
        ];
    }

    public function onRender()
    {
        $user = Auth::getUser();
        $this->page['showAcceptButtons'] = false;
        $this->page['showUnblockButton'] = false;
        $this->page['showUnfriendButton'] = false;

        switch ($this->property('listType')) {
            case 'blocked':
                $this->page['friends'] = $user->listBlockedFriends();
                $this->page['showUnblockButton'] = true;
                break;
            case 'sent':
                $this->page['friends'] = $user->listSentFriendRequests();
                break;
            case 'received':
                $this->page['friends'] = $user->listReceivedFriendRequests();
                $this->page['showAcceptButtons'] = true;
                break;
            case 'friends':
            default:
                $this->page['friends'] = $user->listFriends();
                $this->page['showUnfriendButton'] = true;
                break;
        }

        $this->page['friends']->each(function($friend) {
            $friend->hashId = $friend->getHashId();
        });
    }

    public function onUpdateFriendship()
    {
        $user = Auth::getUser();

        if ($friend = User::findByHashId(post('friend_id'))) {
            switch (post('action')) {
                case 'accept':
                    $user->acceptFriendRequest($friend);
                    break;
                case 'decline':
                    $user->declineFriendRequest($friend);
                    break;
                case 'block':
                    $friend->block($user);
                    break;
                case 'unblock':
                    $friend->unblock($user);
                    break;
                case 'unfriend':
                    $friend->unfriend($user);
                    break;
            }
        }

        return Redirect::refresh();
    }
}
