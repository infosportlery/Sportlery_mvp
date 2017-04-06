<?php

namespace Sportlery\Library\Components;

use Cms\Classes\ComponentBase;
use Cms\Classes\Page;
use Hashids\Hashids;
use Sportlery\Library\Models\Event;
use Sportlery\Library\Models\Location;
use Sportlery\Library\Models\Sport;
use Auth;

class UserEventList extends ComponentBase
{
    /**
     * Returns information about this component, including name and description.
     */
    public function componentDetails()
    {
        return [
            'name' => 'Users Event List',
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
        $this->page['events'] = $this->getUserEvents();
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

    public function getUserEvents()
    {
        //alle upcomming events waar gebruiker aan mee doet
        
        $user = Auth::getUser();

        return $user->events()->get();
    }

    private function getEventTypes()
    {
        return [Event::TYPE_PAID => 'Paid', Event::TYPE_FREE => 'Free'];
    }
}
