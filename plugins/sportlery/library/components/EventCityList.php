<?php

namespace Sportlery\Library\Components;

use Cms\Classes\ComponentBase;
use Cms\Classes\Page;
use Hashids\Hashids;
use Sportlery\Library\Models\Event;
use Sportlery\Library\Models\Location;
use Sportlery\Library\Models\Sport;
use Auth;

class EventCityList extends ComponentBase
{
    /**
     * Returns information about this component, including name and description.
     */
    public function componentDetails()
    {
        return [
            'name' => 'Event City List',
            'description' => 'Display Events per city on Users Sports',
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
        $this->page['events'] = $this->getEventsByCity();
        $this->page['sports'] = $this->getSports();
        $this->page['cities'] = $this->getCities();
        $this->page['eventTypes'] = $this->getEventTypes();
        $this->page['detailsPage'] = $this->property('detailsPage');

        $this->addCss('https://unpkg.com/leaflet@1.0.3/dist/leaflet.css');
        $this->addJs('https://unpkg.com/leaflet@1.0.3/dist/leaflet.js');
    }

    private function getEvents()
    {
        $perPage = $this->property('perPage');
        $hashids = \App::make(Hashids::class);

        $searchParameters = \Input::only(['q', 'event_type', 'sport', 'city', 'past']);

        $events = Event::search($searchParameters)
                    ->orderBy('name', 'asc')
                    ->paginate($perPage);

        $events->each(function($event) use ($hashids) {
            $event->id = $event->getHashId();
            $event->description = str_limit(strip_tags($event->description), 140);
        });

        return $events;
    }

    private function getSports()
    {
        return Sport::orderBy('name', 'asc')->lists('name', 'id');
    }

    private function getCities()
    {
        return Location::distinct()->orderBy('city', 'asc')->lists('city', 'city');
    }

    public function getEventsByCity()
    {
        //alle upcomming events in user city

        $city = Auth::getUser()->city;

        return Event::whereHas('location', function($query) use ($city) {
                return $query->where('city', $city);
            })->paginate(6);
    }

    private function getEventTypes()
    {
        return [Event::TYPE_PAID => 'Paid', Event::TYPE_FREE => 'Free'];
    }
}
