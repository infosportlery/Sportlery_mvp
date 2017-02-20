<?php

namespace Sportlery\Library\Components;

use Cms\Classes\ComponentBase;
use Cms\Classes\Page;
use Hashids\Hashids;
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
            ],
            'detailsPage' => [
                'title'       => 'Details page',
                'description' => 'The page to redirect to when selecting a location',
                'type'        => 'dropdown',
                'showExternalParam' => false,
            ],
        ];
    }

    public function getDetailsPageOptions()
    {
        return Page::sortBy('baseFileName')->lists('baseFileName', 'baseFileName');
    }

    public function onRun()
    {
        $this->page['locations'] = $this->locations();
        $this->page['detailsPage'] = $this->property('detailsPage');
    }

    public function locations()
    {
        $perPage = $this->property('perPage');
        $hashids = \App::make(Hashids::class);

        return Location::orderBy('name', 'asc')->paginate($perPage)->each(function($location) use ($hashids) {
            $location->id = $location->getHashId();
        });
    }
}
