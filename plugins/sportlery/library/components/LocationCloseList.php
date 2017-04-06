<?php

namespace Sportlery\Library\Components;

use Cms\Classes\ComponentBase;
use Cms\Classes\Page;
use Hashids\Hashids;
use Sportlery\Library\Models\Location;
use Sportlery\Library\Models\Sport;
use Auth;

class LocationCloseList extends ComponentBase
{
    /**
     * Returns information about this component, including name and description.
     */
    public function componentDetails()
    {
        return [
            'name' => 'Location Close List',
            'description' => 'Display a list of locations close to the user',
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
        $this->page['locations'] = $this->getLocationsByCity();
        $this->page['detailsPage'] = $this->property('detailsPage');

        $this->addCss('https://unpkg.com/leaflet@1.0.3/dist/leaflet.css');
        $this->addJs('https://unpkg.com/leaflet@1.0.3/dist/leaflet.js');
    }

    public function getLocations()
    {
        $perPage = $this->property('perPage');

        $searchParameters = \Input::only(['q', 'sport', 'location_type', 'city']);

        $locations = Location::search($searchParameters)
                       ->orderBy('name', 'asc')
                       ->paginate($perPage);

        $locations->each(function($location) {
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

    public function getLocationsByCity()
    {
        //alle upcomming events in user city

        $perPage = $this->property('perPage');
        $city = Auth::getUser()->city;
        $sports = Auth::getUser()->sports;

        $searchParameters = \Input::only(['q', 'sport', 'location_type', 'city']);

        $locations = Location::search($searchParameters)
                             ->where('city', $city)
                             // ->whereHas('sports', function($query) use ($sports) {
                             //    return $query->whereIn('id', $sports->lists('id'));
                             ->paginate($perPage);
        
        $locations->each(function($location) {
            $location->id = $location->getHashId();
        });

        return $locations;
    }


    private function getCities()
    {
        return Location::orderBy('city', 'asc')->distinct()->lists('city', 'city');
    }
}
