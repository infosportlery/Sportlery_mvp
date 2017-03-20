<?php

namespace Sportlery\Library\Components;

use Carbon\Carbon;
use Cmgmyr\Messenger\Models\Message;
use Cmgmyr\Messenger\Models\Thread;
use Cms\Classes\ComponentBase;
use Illuminate\Support\Str;
use October\Rain\Exception\AjaxException;

class Chat extends ComponentBase
{
    /**
     * Returns information about this component, including name and description.
     */
    public function componentDetails()
    {
        return [
            'name' => 'Chat',
            'description' => 'Shows a chat thread',
        ];
    }

    public function onRun()
    {
        $user = \Auth::getUser();

        $messages = function ($q) {
            return $q->latest()->limit(10);
        };

        $thread = $user->threads()->with([
            'messages' => $messages,
            'messages.user.avatar'
        ])->where('spr_chats.id', $this->param('id'))->firstOrFail();
        $thread->messages = $thread->messages->reverse();
        $thread->markAsRead($user->id);

        $this->page['chat'] = $thread;
    }

    public function onAddMessage()
    {
        $body = strip_tags(trim(post('message')));

        if (Str::length($body) === 0) {
            throw new AjaxException(['error' => 'Please enter a message']);
        }

        $user = \Auth::getUser();
        /** @var Thread $thread */
        $thread = $user->threads()->where('spr_chats.id', $this->param('id'))->firstOrFail();

        $message = new Message();
        $message->body = $body;
        $message->user()->associate($user);
        $thread->messages()->save($message);
        $thread->markAsRead($user->id);

        $this->page['message'] = $message;

        return [
            '@#chat-messages' => $this->renderPartial('chat::message'),
        ];
    }

    public function onCheckNewMessages()
    {
        $since = strtotime(post('since'));

        if ($since === false) {
            return [];
        }

        $since = Carbon::createFromTimestamp($since);

        $user = \Auth::getUser();
        $thread = $user->threads()->where('spr_chats.id', $this->param('id'))->firstOrFail(['spr_chats.id']);

        $messages = $thread->messages()
                           ->with('user')
                           ->where('user_id', '!=', $user->id)
                           ->where('created_at', '>', $since)
                           ->orderBy('created_at', 'asc')
                           ->get();

        if (!count($messages)) {
            return null;
        }

        $thread->markAsRead($user->id);

        $result = '';

        foreach ($messages as $message) {
            $result .= $this->renderPartial('chat::message', compact('message'));
        }

        return [
            '@#chat-messages' => $result,
        ];
    }

    public function onLoadMoreMessages()
    {
        $until = strtotime(post('until'));

        if ($until === false) {
            return null;
        }

        $until = Carbon::createFromTimestamp($until);

        $user = \Auth::getUser();
        $thread = $user->threads()->where('spr_chats.id', $this->param('id'))->firstOrFail(['spr_chats.id']);

        $messages = $thread->messages()
                           ->with('user')
                           ->where('created_at', '<', $until)
                           ->orderBy('created_at', 'desc')
                           ->limit(50)
                           ->get();

        if (!count($messages)) {
            return null;
        }

        $result = '';

        foreach ($messages as $message) {
            $result = $this->renderPartial('chat::message', compact('message')).$result;
        }

        return [
            '^#chat-messages' => $result,
        ];
    }
}
