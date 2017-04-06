<?php

namespace Sportlery\Library\Behaviors;

use RainLab\User\Models\User;
use Sportlery\Library\Classes\EventJoinStatus;
use Sportlery\Library\Models\Event;
use System\Classes\ModelBehavior;

class UserEventsModel extends ModelBehavior
{
    /**
     * Initialize the friendable model behavior.
     *
     * @param  \October\Rain\Database\Model  $model
     */
    public function __construct($model)
    {
        parent::__construct($model);

        $model->belongsToMany['events'] = [
            Event::class,
            'table' => 'spr_event_user',
            'pivot' => ['status',],
            'timestamps' => true,
            'key' => 'user_id',
            'otherKey' => 'event_id',
        ];

        $model->belongsToMany['ownedEvents'] = [
            Event::class,
            'table' => 'spr_events',
            'key' => 'user_id',
        ];

        $model->belongsToMany['invitedToEvents'] = [
            Event::class,
            'table' => 'spr_event_invites',
            'pivot' => ['status', 'invited_by'],
            'timestamps' => true,
            'key' => 'user_id',
            'otherKey' => 'event_id',
        ];

        $model->belongsToMany['invitedToEventsBy'] = [
            User::class,
            'table' => 'spr_event_invites',
            'pivot' => ['status', 'event_id'],
            'timestamps' => true,
            'key' => 'user_id',
            'otherKey' => 'invited_by',
        ];

        $model->belongsToMany['usersInvitedToEvents'] = [
            User::class,
            'table' => 'spr_event_invites',
            'pivot' => ['status', 'event_id'],
            'timestamps' => true,
            'key' => 'invited_by',
            'otherKey' => 'user_id',
        ];
    }

    public function getEventStatus($event)
    {
        return $this->model->events()->newPivotStatementForId($event->getKey())->pluck('status');
    }

    /**
     * Join the given event.
     *
     * @param  Event  $event
     */
    public function joinEvent($event)
    {
        $this->model->events()->sync([$event->getKey() => ['status' => EventJoinStatus::GOING]], false);
    }

    public function cancelJoinEvent($event)
    {
        $this->model->events()->updateExistingPivot($event->getKey(), ['status' => EventJoinStatus::CANCELED]);
    }

    public function interestEvent($event)
    {
        $this->model->events()->sync([$event->getKey() => ['status' => EventJoinStatus::INTERESTED]], false);
    }

    public function cancelInterestEvent($event)
    {
        $this->model->events()->newPivotStatementForId($event->getKey())->where('status', EventJoinStatus::INTERESTED)->delete();
    }

    public function sendEventInvites($event, array $friendIds)
    {
        $friendsAlreadyGoing = $this->getFriendsGoingToEvent($event, $friendIds);

        $friendIds = array_diff($friendIds, $friendsAlreadyGoing);
        $syncData = array_fill_keys($friendIds, ['event_id' => $event->getKey()]);

        return $this->model->usersInvitedToEvents()->sync($syncData,false);
    }

    public function getFriendsInvitedToEvent($event)
    {
        return $this->model->usersInvitedToEvents()->wherePivot('event_id', $event->getKey())->lists('user_id');
    }

    public function getFriendsGoingToEvent($event, array $friendIds = [])
    {
        $query = $this->model->events()->newPivotStatement()
                           ->where('event_id', $event->getKey())
                           ->where('user_id', '!=', $this->model->getKey())
                           ->where('status', EventJoinStatus::GOING);

        if (count($friendIds)) {
            $query->whereIn('user_id', $friendIds);
        }

        return $query->lists('user_id');
    }
}
