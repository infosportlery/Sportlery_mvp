<?php

namespace Sportlery\Library\Behaviors;

use System\Classes\ModelBehavior;
use Cmgmyr\Messenger\Models\Message;
use Cmgmyr\Messenger\Models\Models;
use Cmgmyr\Messenger\Models\Participant;
use Cmgmyr\Messenger\Models\Thread;

/**
 * Copied from the Messagable trait in the Messenger package.
 *
 * @see \Cmgmyr\Messenger\Traits\Messagable
 */
class MessagableModel extends ModelBehavior
{
    /**
     * Create a new messagablemodel behavior instance.
     *
     * @param \October\Rain\Database\Model $model
     */
    public function __construct($model)
    {
        parent::__construct($model);

        $model->hasMany['messages'] = Models::classname(Message::class);
        $model->hasMany['participants'] = Models::classname(Participant::class);
        $model->belongsToMany['threads'] = [
            Models::classname(Thread::class),
            'table' => Models::table('participants'),
            'key' => 'user_id',
            'otherKey' => 'thread_id'
        ];
    }

    /**
     * Returns the new messages count for user.
     *
     * @return int
     */
    public function newThreadsCount()
    {
        return $this->threadsWithNewMessages()->count();
    }

    /**
     * Returns all threads with new messages.
     *
     * @return \Illuminate\Database\Eloquent\Relations\belongsToMany
     */
    public function threadsWithNewMessages()
    {
        return $this->model->threads()
                    ->where(function ($q) {
                        $q->whereNull(Models::table('participants') . '.last_read');
                        $q->orWhere(Models::table('threads') . '.updated_at', '>', $this->model->getConnection()->raw($this->model->getConnection()->getTablePrefix() . Models::table('participants') . '.last_read'));
                    })->get();
    }
}
