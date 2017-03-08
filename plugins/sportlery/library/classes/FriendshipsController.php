<?php

namespace Sportlery\Library\Classes;

use Redirect;
use Illuminate\Routing\Controller;
use October\Rain\Support\Facades\Flash;
use Sportlery\Library\Models\User;

class FriendshipsController extends Controller
{
    public function addFriend()
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

    public function unfriend()
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

    public function block()
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

    public function unblock()
    {
        $user = \Auth::getUser();
        $otherUser = User::findByHashId(post('friend_id'));

        if ($user->unblock($otherUser)) {
            Flash::success('Successfully blocked user.');
        } else {
            Flash::error('Failed to block.');
        }

        return Redirect::back();
    }

}
