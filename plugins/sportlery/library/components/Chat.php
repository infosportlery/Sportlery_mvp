<?php

namespace Sportlery\Library\Components;

use Redirect;
use Carbon\Carbon;
use Cmgmyr\Messenger\Models\Message;
use Cmgmyr\Messenger\Models\Thread;
use Cms\Classes\ComponentBase;
use Hashids\Hashids;
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

    public function init()
    {
        $this->addComponent('RainLab\User\Components\Session', 'session', [
            'security' => 'user',
            'redirect' => 'login',
        ]);
    }

    public function onRun()
    {
        $user = Auth::getUser();

        $messages = function ($q) {
            return $q->latest()->limit(25);
        };

        $thread = $user->threads()->with([
            'messages' => $messages,
            'messages.user.avatar'
        ])->where('spr_chats.id', '=', $this->getThreadId())->first();

        if (!$thread) {
            return Redirect::to($this->controller->pageUrl('message-center'));
        }

        $thread->messages = $thread->messages->reverse();
        $thread->markAsRead($user->id);
        $thread->messageCount = $thread->messages()->count();

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
        $thread = $user->threads()->where('spr_chats.id', $this->getThreadId())->first();

        if (!$thread) {
            return Redirect::to($this->controller->pageUrl('message-center'));
        }

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
        $thread = $user->threads()->where('spr_chats.id', $this->getThreadId())->first(['spr_chats.id']);

        if (!$thread) {
            return Redirect::to($this->controller->pageUrl('message-center'));
        }

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
        $thread = $user->threads()->where('spr_chats.id', $this->getThreadId())->first(['spr_chats.id']);

        if (!$thread) {
            return Redirect::to($this->controller->pageUrl('message-center'));
        }

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

    private function getThreadId()
    {
        $decoded = app(Hashids::class)->decode($this->param('id'));

        return count($decoded) > 0 ? reset($decoded) : 0;
    }
}
