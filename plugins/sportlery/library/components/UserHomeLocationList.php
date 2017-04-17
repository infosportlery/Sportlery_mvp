<?php

namespace Sportlery\Library\Components;

use Cms\Classes\ComponentBase;
use Cms\Classes\Page;
use Hashids\Hashids;
use Illuminate\Support\Facades\Input;
use Sportlery\Library\Models\Event;
use Sportlery\Library\Models\Location;
use Sportlery\Library\Models\Sport;
use Sportlery\Library\Classes\EventJoinStatus;
use Auth;

class UserHomeLocationList extends ComponentBase
{
    private $searchParameters = [];

    /**
     * Returns information about this component, including name and description.
     */
    public function componentDetails()
    {
        return [
            'name' => 'User home Locations List',
            'description' => 'Display a list of events from the user',
        ];
    }

    public function defineProperties()
    {
        return [
            'perPage' => [
                'title'             => 'Per page',
                'description'       => 'The number of events displayed on each page',
                'default'           => 10,
                'type'              => 'string',
                'validationPattern' => '^[0-9]+$',
                'validationMessage' => 'The per page field can only contain numbers'
            ],
            'detailsPage' => [
                'title'       => 'Details page',
                'description' => 'The page to redirect to when selecting a event',
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
        $this->searchParameters = \Input::only(['q', 'event_type', 'sport', 'city', 'past']);

        $this->page['locations'] = $this->getLocations();
        $this->page['sports'] = $this->getSports();
        $this->page['cities'] = $this->getCities();
        $this->page['listTypes'] = $this->getListTypes();
        $this->page['eventTypes'] = $this->getLocationTypes();
        $this->page['detailsPage'] = $this->property('detailsPage');

        $this->addCss('https://unpkg.com/leaflet@1.0.3/dist/leaflet.css');
        $this->addJs('https://unpkg.com/leaflet@1.0.3/dist/leaflet.js');
    }

    private function getLocations()
    {
        $perPage = $this->property('perPage');
        $listType = trim(Input::get('list_type'));
        $user = Auth::getUser();

        if ($listType == 'frequented') {
            $locationIds = $user->events()->wherePivot('status', EventJoinStatus::GOING)
                ->where('starts_at', '<', date('Y-m-d H:i:s'))->lists('location_id');
            $locations = Location::whereIn('id', $locationIds);
        } elseif ($listType === 'suggested') {
            $searchParameters = array_except($this->searchParameters, ['city', 'sport']);
            $sports = $user->sports()->lists('id');
            $locations = Location::search($searchParameters)->where('city', $user->city)
                ->whereHas('sports', function ($query) use ($sports) {
                    return $query->whereIn('id', $sports);
            });
        } else {
            $locations = Location::search($this->searchParameters);
        }

        return $locations->where('is_hidden', 0)->orderBy('name', 'asc')->paginate($perPage);
    }

    private function getSports()
    {
        return Sport::orderBy('name', 'asc')->lists('name', 'id');
    }

    private function getCities()
    {
        return Location::distinct()->orderBy('city', 'asc')->lists('city', 'city');
    }

    public function getUserEventsLocations()
    {
        $user = Auth::getUser();

        return $user->locations()->search($this->searchParameters)->where('is_hidden', 0)->get();
    }

    private function getLocationTypes()
    {
        return [Event::TYPE_PAID => 'Paid', Event::TYPE_FREE => 'Free'];
    }

    private function getListTypes()
    {
        return ['frequented' => 'Frequented Locations', 'suggested' => 'Suggested Locations'];
    }
}
