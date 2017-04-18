<?php

namespace Sportlery\Library\Components;

use Auth;
use Cms\Classes\ComponentBase;
use Illuminate\Support\Facades\Redirect;
use October\Rain\Support\Facades\Flash;
use RainLab\Translate\Models\Message;
use Sportlery\Library\Classes\FriendshipStatus;
use Rainlab\User\Models\User;

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
        $levels = [
            1 => Message::trans('Beginner'),
            2 => Message::trans('Gevorderd'),
            3 => Message::trans('Pro'),
        ];
        $this->page['profile']->sports->each(function($sport) use ($levels) {
            $sport->level = $levels[$sport->pivot->level];
        });
        $this->page['profileHashId'] = $this->param('id');
        $this->page['authenticated'] = Auth::check();

        if (!$this->page['authenticated']) {
            return;
        }

        $friendshipStatus = \Auth::getUser()->getFriendshipStatus($this->page['profile']);
        $this->page['friendshipNone'] = is_null($friendshipStatus);
        $this->page['blocked'] = $friendshipStatus === FriendshipStatus::BLOCKED;
        $this->page['isFriend'] = $friendshipStatus === FriendshipStatus::ACCEPTED;
        $this->page['friendshipPending'] = $friendshipStatus === FriendshipStatus::PENDING;
        $this->page['friendshipDeclined'] = $friendshipStatus === FriendshipStatus::DECLINED;
    }

    public function onAddFriend()
    {
        $user = \Auth::getUser();
        $otherUser = User::findByHashId(post('friend_id'));

        if ($user->sendFriendRequest($otherUser)) {
            Flash::success('Friendship request sent!');
        } else {
            Flash::error('Failed to send friendship request, friendship already exists.');
        }

        return Redirect::back();
    }

    public function onUnfriend()
    {
        $user = \Auth::getUser();
        $otherUser = User::findByHashId(post('friend_id'));

        if ($user->unfriend($otherUser)) {
            Flash::success('Successfully unfriended user.');
        } else {
            Flash::error('Failed to unfriend.');
        }

        return Redirect::back();
    }

    public function onBlock()
    {
        $user = \Auth::getUser();
        $otherUser = User::findByHashId(post('friend_id'));

        if ($user->block($otherUser)) {
            Flash::success('Successfully blocked user.');
        } else {
            Flash::error('Failed to block.');
        }

        return Redirect::back();
    }

    public function onUnblock()
    {
        $user = \Auth::getUser();
        $otherUser = User::findByHashId(post('friend_id'));

        if ($user->unblock($otherUser)) {
            Flash::success('Successfully unblocked user.');
        } else {
            Flash::error('Failed to block.');
        }

        return Redirect::back();
    }
}
