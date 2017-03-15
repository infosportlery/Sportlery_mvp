<?php

namespace Sportlery\Library\Components;

use Cms\Classes\ComponentBase;
use Sportlery\Library\Classes\EventJoinStatus;
use Sportlery\Library\Models\Event;

class EventProfile extends ComponentBase
{
    /**
     * Returns information about this component, including name and description.
     */
    public function componentDetails()
    {
        return [
            'name' => 'Event Profile',
            'description' => 'Display an event profile',
        ];
    }

    public function init()
    {
        $this->addComponent('RainLab\User\Components\Session', 'session', ['security' => 'all']);
    }

    public function onRun()
    {
        $this->page['event'] = Event::findByHashId($this->param('id'));

        if ($this->page['user']) {
            $this->setEventJoinContext();
        }
    }

    public function onUpdateEventJoinStatus()
    {
        $user = \Auth::getUser();

        if ($event = Event::findByHashId(post('event_id'))) {
            switch (post('action')) {
                case 'join':
                    $user->joinEvent($event);
                    break;
                case 'cancel_join':
                    $user->cancelJoinEvent($event);
                    break;
                case 'interest':
                    $user->interestEvent($event);
                    break;
                case 'cancel_interest':
                    $user->cancelInterestEvent($event);
                    break;
            }
        }

        return \Redirect::refresh();
    }

    public function onSendInvites()
    {
        $user = \Auth::getUser();

        if (!$user) {
            \Flash::error('You must be logged in to do that.');

            return \Redirect::back();
        }

        $friendIds = post('friend_id');

        if (!$friendIds || count($friendIds) == 0) {
            \Flash::error('Please select one or more friends to invite.');

            return \Redirect::back();
        }

        if (is_null($event = Event::findByHashId(post('event_id')))) {
            \Flash::error('Sorry, the event could not be found.');

            return \Redirect::back();
        }

        $friendsToInvite = $user->findFriendIdsByHashIds($friendIds);

        $user->sendEventInvites($event, $friendsToInvite);

        return \Redirect::refresh();
    }

    private function setEventJoinContext()
    {
        $user = $this->page['user'];
        $eventStatus = $user->getEventStatus($this->page['event']);

        $this->page['isGoing'] = $eventStatus === EventJoinStatus::GOING;
        $this->page['isInterested'] = $eventStatus === EventJoinStatus::INTERESTED;

        $this->page['friends'] = $this->page['user']->listFriends();
        $this->page['hasFriends'] = !$this->page['friends']->isEmpty();

        if ($this->page['hasFriends']) {
            $friendsInvited = $this->page['user']->getFriendsInvitedToEvent($this->page['event']);
            $friendIds = $this->page['friends']->modelKeys();
            $this->page['friendsInvited'] = $friendsInvited;

            if (count($friendIds) === count($friendsInvited)) {
                // All friends are already invited.
                $this->page['hasFriends'] = false;
            }
        }

        $friendsGoing = $this->page['user']->getFriendsGoingToEvent($this->page['event']);

        if (count($friendsGoing) === $this->page['friends']->count()) {
            $this->page['hasFriends'] = false;
        }

        $this->page['friendsGoing'] = $this->page['friends']->only($friendsGoing);
    }
}
