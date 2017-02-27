<?php

namespace Sportlery\Library\Components;

use Cms\Classes\ComponentBase;
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

    public function onRun()
    {
        $this->page['event'] = Event::findByHashId($this->param('id'));
    }
}
