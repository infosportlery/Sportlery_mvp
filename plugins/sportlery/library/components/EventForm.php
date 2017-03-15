<?php

namespace Sportlery\Library\Components;

use Cms\Classes\ComponentBase;
use Validator;
use Input;
use Redirect;
use Flash;
use Sportlery\Library\Models\Event;
use Sportlery\Library\Models\Location;
/**
* 
*/
class EventForm extends ComponentBase
{
	
	 public function componentDetails()
    {
        return [
            'name' => 'Event Form',
            'description' => 'A form for events',
        ];
    }

    public function onRun() {
        $this->page['locations'] = $this->getLocations();
    }

    public function onCreate() 
    {
        $validator = Validator::make(
            [ 
                'name' => Input::get('name'),
                'description' => Input::get('description'),
                'price' => Input::get('price'),
                'starts_at' => Input::get('starts_at'),
                'ends_at' => Input::get('ends_at'),

            ],
            [
                'name' => 'required|min:8',
                'description' => 'required|min:30',
                'price' => 'integer',
                'starts_at' => 'required|date_format:"Y-m-d H:i:s"|before:ends_at',
                'ends_at' => 'required|date_format:"Y-m-d H:i:s"|after:starts_at',

            ]
        );


        if($validator->fails()) {
            //sendbacktothing
            return Redirect::back()->withErrors($validator);

        } else {
            //sendtodb

            $event = new Event();

            $event->name = Input::get('name');
            $event->slug = $this->generateRandomString(8);
            $event->description = Input::get('description');
            $event->price = Input::get('price');
            $event->starts_at = Input::get('starts_at');
            $event->ends_at = Input::get('ends_at');
            $event->location_id = Input::get('location');

            $event->save();        

            Flash::success('You\'ve added an Event!');

            return Redirect::back();
        }
    }

    public function onUpdate($slug)
    {
        $event = Event::where('slug', '=', $slug)->first();

        echo $event;

    }

    public function onDelete()
    {
    	
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