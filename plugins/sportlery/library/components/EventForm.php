<?php

namespace Sportlery\Library\Components;

use Auth;
use Carbon\Carbon;
use Cms\Classes\ComponentBase;
use Validator;
use Input;
use Redirect;
use Flash;
use Sportlery\Library\Models\Event;
use Sportlery\Library\Models\Location;

class EventForm extends ComponentBase
{
    public function componentDetails()
    {
        return [
            'name' => 'Event Form',
            'description' => 'A form for events',
        ];
    }

    public function init()
    {
        $this->addComponent(LocationPicker::class, 'locationPicker', []);
    }

    public function onRun()
    {
        $this->page['locations'] = $this->getLocations();
        $this->page['user'] = \Auth::getUser();

        if ($eventId = $this->param('id')) {
            $this->page['event'] = $this->page['user']->events()->whereHashId($eventId)->first();

            if (!$this->page['event']) {
                Flash::error('Sorry, the activity you requested could not be found.');

                return Redirect::to($this->controller->pageUrl('home'));
            }
        }
    }

    public function onCreate()
    {
        $data = Input::all();
        $data['description'] = strip_tags($data['description']);

        $validator = Validator::make($data, [
            'name' => 'required|min:8',
            'description' => 'required|min:30',
            'price' => 'numeric',
            'max_attendees' => 'integer',
            'starts_at' => 'required|date_format:"Y-m-d H:i"|before:ends_at',
            'ends_at' => 'required|date_format:"Y-m-d H:i"|after:starts_at',
            'location' => 'required|exists:spr_locations,id'
        ]);

        if ($validator->fails()) {
            return Redirect::back()->withInput()->withErrors($validator);
        }

        $user = Auth::getUser();

        $event = new Event();

        $event->name = Input::get('name');
        $event->slug = str_random(8).'-'.str_slug($event->name);
        $event->description = Input::get('description');
        $event->price = Input::get('price') ?: 0;
        $event->max_attendees = Input::get('max_attendees') ?: 0;
        $event->current_attendees = 0;
        $event->starts_at = Carbon::createFromFormat('Y-m-d H:i', Input::get('starts_at'));
        $event->ends_at = Carbon::createFromFormat('Y-m-d H:i', Input::get('ends_at'));
        $event->user_id = $user->id;
        $event->location_id = Input::get('location');

        $user->ownedEvents()->save($event);

        Flash::success('You\'ve added an event!');

        return Redirect::to($this->controller->pageUrl('locations'));
    }

    public function onUpdate()
    {
        $event = Event::findByHashId($this->param('id'));

        if ($event->user_id !== Auth::getUser()->id) {
            return Redirect::back();
        }

        $event->name = Input::get('name');
        $event->description = Input::get('description');
        $event->price = Input::get('price');
        $event->starts_at = Input::get('starts_at');
        $event->ends_at = Input::get('ends_at');
        $event->location_id = Input::get('location');

        $event->save();

        Flash::success('You\'ve Changed the event!');

        return Redirect::back();
    }

    public function onDelete()
    {
        $event = Event::findByHashId($this->param('id'));

        $event->delete();

        return Redirect::back();
    }

    private function getLocations() {
        return Location::orderBy('name', 'asc')
                       ->orWhere('is_hidden', 0)
                       ->orWhere('user_id', Auth::getUser()->id)
                       ->lists('name', 'id');
    }

}
