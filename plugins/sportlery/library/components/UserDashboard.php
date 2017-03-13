<?php

namespace Sportlery\Library\Components;

use Cms\Classes\ComponentBase;

class UserDashboard extends ComponentBase
{
    /**
     * Returns information about this component, including name and description.
     */
    public function componentDetails()
    {
        return [
            'name' => 'User Dashboard',
            'description' => 'Use This for the Users dashboard',
        ];
    }

    public function onRun()
    {
        $user = \Auth::getUser();
    }
}
