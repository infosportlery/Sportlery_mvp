<?php

namespace Sportlery\Library\Components;

use Auth;
use Cmgmyr\Messenger\Models\Participant;
use Cmgmyr\Messenger\Models\Thread;
use Cms\Classes\ComponentBase;

class ChatList extends ComponentBase
{
    /**
     * Returns information about this component, including name and description.
     */
    public function componentDetails()
    {
        return [
            'name' => 'Chat List',
            'description' => 'Shows a list of active chats for the current user.',
        ];
    }

    public function onRun()
    {
        $this->page['chats'] = $this->getChats();
    }

    private function getChats()
    {
        $userId = \Auth::getUser()->id;
        $messages = function ($q) {
            $q->latest()->take(1);
        };

        $threads = Thread::forUser($userId)->latest('updated_at')->with(['messages' => $messages, 'messages.user'])->get();

        $pms = $threads->filter(function ($thread) {
            return $thread->subject === '_pm';
        })->load(['participants' => function($q) use ($userId) {
            $q->where('user_id', '!=', $userId)->take(1);
        }, 'participants.user.avatar'])->each(function ($thread) {
            $otherUser = $thread->participants->first()->user;
            $thread->subject = $otherUser->first_name.' '.$otherUser->last_name;
            $thread->avatar = $otherUser->avatar;
            $thread->isPM = true;
        });

        return $threads->each(function ($thread) use ($userId) {
            $thread->lastMessage = $thread->messages->isEmpty() ? null : $thread->messages->first();
            $thread->unreadMessagesCount = $thread->userUnreadMessagesCount($userId);
        });
    }

    public function onFetchFriends()
    {
        $user = Auth::getUser();
        $pmIds = Thread::forUser($user->id)->where('subject', '_pm')->lists('id');
        $pmFriends = Participant::whereIn('thread_id', $pmIds)->where('user_id', '!=', $user->id)->lists('user_id');

        $friends = $user->listFriendsNotIn($pmFriends->all());
    }
}
