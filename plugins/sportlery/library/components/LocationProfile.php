<?php

namespace Sportlery\Library\Components;

use Cms\Classes\ComponentBase;
use Hashids\Hashids;
use Sportlery\Library\Models\Location;

class LocationProfile extends ComponentBase
{
    /**
     * Returns information about this component, including name and description.
     */
    public function componentDetails()
    {
        return [
            'name' => 'Location Profile',
            'description' => 'Display a location profile',
        ];
    }

    public function onRun()
    {
        $this->page['location'] = Location::whereHashId($this->param('id'))->first();
    }
}
