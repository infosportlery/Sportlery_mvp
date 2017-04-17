<?php

namespace Sportlery\Library\Components;

use Cms\Classes\ComponentBase;

class NotificationCounts extends ComponentBase
{
    /**
     * Returns information about this component, including name and description.
     */
    public function componentDetails()
    {
        return [
            'name' => 'Notification counts',
            'description' => 'Assign counts for notifications to the view'
        ];
    }

    public function onRun()
    {
        $user = \Auth::getUser();

        if (!$user) {
            return;
        }

        $friendRequests = $user->countReceivedFriendRequests();
        $unreadMessages = $user->newThreadsCount();
        $notificationCount = $friendRequests + $unreadMessages;

        $this->page['friendRequestCount'] = $friendRequests;
        $this->page['unreadMessageCount'] = $unreadMessages;
        $this->page['notificationCount'] = $notificationCount;
    }
}
