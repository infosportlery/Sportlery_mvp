<?php

namespace Sportlery\Library\Components;

use Auth;
use Cmgmyr\Messenger\Models\Participant;
use Cmgmyr\Messenger\Models\Thread;
use Cms\Classes\ComponentBase;
use Hashids\Hashids;
use Illuminate\Support\Facades\Redirect;

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
        $userId = Auth::getUser()->id;
        // $userId = $this->page['user']->id;
        $messages = function ($q) {
            $q->latest()->take(1);
        };

        $threads = Thread::forUser($userId)->latest('updated_at')->with(['messages' => $messages, 'messages.user'])->get();

        $threads->filter(function ($thread) {
            return $thread->subject === '_pm' || $thread->subject === '_group_pm';
        })->load(['participants' => function($q) use ($userId) {
            $q->where('user_id', '!=', $userId);
        }, 'participants.user.avatar'])->each(function ($thread) {
            $names = $thread->participants->map(function($participant) {
                return $participant->user->name.' '.$participant->user->surname;
            });

            if ($names->count() > 5) {
                $names = $names->slice(0, 5);
                $names->push($names->count().' more');
            }

            $thread->subject = $names->implode(', ');
            $thread->avatar = null;

            if ($thread->participants->count() === 1) {
                $thread->avatar = $thread->participants->first()->user->getAvatarThumb(40);
            }
        });

        $hashIds = app(Hashids::class);

        return $threads->each(function ($thread) use ($userId, $hashIds) {
            $thread->id = $hashIds->encode($thread->id);
            $thread->lastMessage = $thread->messages->isEmpty() ? null : $thread->messages->first();
            $thread->unreadMessagesCount = $thread->userUnreadMessagesCount($userId);
        });
    }

    public function onFetchFriends()
    {
        $user = Auth::getUser();
        $pmIds = Thread::forUser($user->id)->where('subject', '_pm')->lists('id');
        $pmFriends = Participant::whereIn('thread_id', $pmIds)->where('user_id', '!=', $user->id)->lists('user_id');

        $this->page['friends'] = $user->listFriendsNotIn($pmFriends->all(), ['avatar']);

        return ['#new-chat-friends' => $this->renderPartial('chatList::new-chat')];
    }

    public function onCreateChat()
    {
        $user = Auth::getUser();
        $friendIds = post('friend_id');

        if (!is_array($friendIds) || empty($friendIds)) {
            return Redirect::refresh();
        }
        $hashIds = app(Hashids::class);

        $friendIds = array_filter(array_map(function($friendId) use ($hashIds) {
            $decoded = $hashIds->decode($friendId);

            return is_null($decoded) ? null : reset($decoded);
        }, $friendIds));

        if (empty($friendIds)) {
            return Redirect::refresh();
        }

        $thread = Thread::create(['subject' => count($friendIds) > 1 ? '_group_pm' : '_pm']);
        $thread->addParticipant($user->id, ...$friendIds);

        return Redirect::refresh();
    }
}
