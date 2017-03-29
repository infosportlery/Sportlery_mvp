<?php

namespace Sportlery\Library\Components;

use Auth;
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

    public function onRun()
    {
        $this->page['locations'] = $this->getLocations();

        if ($eventId = $this->param('id')) {
            
            $unscrabled = 
            $this->page['event'] = Event::findByHashId($eventId);
        }
    }

    public function onCreate()
    {
        $validator = Validator::make(Input::all(), [
            'name' => 'required|min:8',
            'description' => 'required|min:30',
            'price' => 'numeric',
            'starts_at' => 'required|date_format:"Y-m-d H:i:s"|before:ends_at',
            'ends_at' => 'required|date_format:"Y-m-d H:i:s"|after:starts_at',
            'location' => 'required|exists:spr_locations,id'
        ]);

        if ($validator->fails()) {
            //sendbacktothing
            return Redirect::back()->withInput()->withErrors($validator);
        } else {
            $user = Auth::getUser();
            $event = new Event();

            $event->name = Input::get('name');
            $event->slug = $this->generateRandomString(8);
            $event->description = Input::get('description');
            $event->price = Input::get('price');
            $event->starts_at = Input::get('starts_at');
            $event->ends_at = Input::get('ends_at');
            $event->user_id = $user->id;
            $event->location_id = Input::get('location');

            $user->events()->status = 1;

            $user->events()->save($event);

            Flash::success('You\'ve added an Event!');

            return Redirect::back();
        }
    }

    public function onUpdate()
    {

        $event = Event::findByHashId($this->param('id'));

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

        Flash::success('AWESOME! No more event!');

        return Redirect::back();
    }

    public function generateRandomString($length) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for($i = 0; $i < $length; $i++){
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    private function getLocations() {
        return Location::orderBy('name', 'asc')->lists('name', 'id');
    }

}
