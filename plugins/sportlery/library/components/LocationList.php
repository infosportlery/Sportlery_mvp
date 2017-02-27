<?php

namespace Sportlery\Library\Components;

use Cms\Classes\ComponentBase;
use Cms\Classes\Page;
use Hashids\Hashids;
use Sportlery\Library\Models\Location;
use Sportlery\Library\Models\Sport;

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
        $this->page['sports'] = $this->getSports();
        $this->page['cities'] = $this->getCities();
        $this->page['locationTypes'] = $this->getLocationTypes();
        $this->page['locations'] = $this->getLocations();
        $this->page['detailsPage'] = $this->property('detailsPage');
    }

    public function getLocations()
    {
        $perPage = $this->property('perPage');
        $hashids = \App::make(Hashids::class);

        $searchParameters = \Input::only(['q', 'sport', 'location_type', 'city']);

        return Location::search($searchParameters)
                       ->orderBy('name', 'asc')
                       ->paginate($perPage)
                       ->each(function($location) use ($hashids) {
                           $location->id = $location->getHashId();
                       });
    }

    private function getSports()
    {
        return Sport::orderBy('name','asc')->lists('name', 'id');
    }

    private function getLocationTypes()
    {
        return [0 => 'Paid', 1 => 'Public'];
    }

    private function getCities()
    {
        return Location::orderBy('city', 'asc')->distinct()->lists('city', 'city');
    }
}
