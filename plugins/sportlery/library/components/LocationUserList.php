<?php

namespace Sportlery\Library\Components;

use Cms\Classes\ComponentBase;
use Cms\Classes\Page;
use Hashids\Hashids;
use Sportlery\Library\Models\Location;
use Sportlery\Library\Models\Sport;

class LocationUserList extends ComponentBase
{
    /**
     * Returns information about this component, including name and description.
     */
    public function componentDetails()
    {
        return [
            'name' => 'Location User List',
            'description' => 'Display a list of locations that the user has been to',
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
        $this->page['locations'] = $this->getLocationsByEventsAttendedByAtLocation();
        $this->page['detailsPage'] = $this->property('detailsPage');

        $this->addCss('https://unpkg.com/leaflet@1.0.3/dist/leaflet.css');
        $this->addJs('https://unpkg.com/leaflet@1.0.3/dist/leaflet.js');
    }

    public function getLocations()
    {
        $perPage = $this->property('perPage');
        $hashids = \App::make(Hashids::class);

        $searchParameters = \Input::only(['q', 'sport', 'location_type', 'city']);

        $locations = Location::search($searchParameters)
                       ->orderBy('name', 'asc')
                       ->paginate($perPage);

        $locations->each(function($location) use ($hashids) {
            $location->id = $location->getHashId();
        });

        return $locations;
    }

    private function getSports()
    {
        return Sport::orderBy('name','asc')->lists('name', 'id');
    }

    private function getLocationTypes()
    {
        return [0 => 'Paid', 1 => 'Public'];
    }

    public function getLocationsByEventsAttendedByAtLocation() 
    {

        $perPage = $this->property('perPage');
        $user = Auth::getUser();

        $events = $user->events()->with('location')->get();

        $locations = $events->pluck('location');

        $locations->each(function($location) use ($hashids) {
            $location->id = $location->getHashId();
        });


        return $locations;
    }

    private function getCities()
    {
        return Location::orderBy('city', 'asc')->distinct()->lists('city', 'city');
    }
}
