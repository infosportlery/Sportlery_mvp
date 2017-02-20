<?php

namespace Sportlery\Library\Components;

use Cms\Classes\ComponentBase;
use Sportlery\Library\Models\Location;

class LocationList extends ComponentBase
{
    /**
     * Returns information about this component, including name and description.
     */
    public function componentDetails()
    {
        return [
            'name' => 'Location List',
            'description' => 'Display a list of locations',
        ];
    }

    public function defineProperties()
    {
        return [
            'perPage' => [
                'title'             => 'Per page',
                'description'       => 'The number of locations displayed on each page',
                'default'           => 10,
                'type'              => 'string',
                'validationPattern' => '^[0-9]+$',
                'validationMessage' => 'The per page field can only contain numbers'
            ]
        ];
    }

    public function locations()
    {
        $perPage = $this->property('perPage');

        return Location::orderBy('name', 'asc')->paginate($perPage);
    }
}
