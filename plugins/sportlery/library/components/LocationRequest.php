<?php

namespace Sportlery\Library\Components;

use Auth;
use Cms\Classes\ComponentBase;
use Validator;
use Input;
use Redirect;
use Flash;
use Sportlery\Library\Models\Location;

class LocationRequest extends ComponentBase
{
    public function componentDetails()
    {
        return [
            'name' => 'Location request',
            'description' => 'A users way to add a location to our list',
        ];
    }

    public function onRun()
    {
        $this->page['locations'] = $this->getLocations();


    }

    public function onCreate()
    {
        $validator = Validator::make(Input::all(), [
            'name' => 'required|min:8',
            'description' => 'required|min:30',
            'street' => 'required',
            'url' => 'required|active_url',
            'city' => 'required',
            'zip_code' => 'required',
        ]);

        if ($validator->fails()) {
            //sendbacktothing
            return Redirect::back()->withInput()->withErrors($validator);
        } else {
            $user = Auth::getUser();
            $location = new Location();

            $location->name = Input::get('name');
            $location->slug = $this->generateRandomString(8);
            $location->description = Input::get('description');
            $location->price = Input::get('price');
            $location->starts_at = Input::get('starts_at');
            $location->ends_at = Input::get('ends_at');
            $location->user_id = $user->id;
            $location->location_id = Input::get('location');

            $location->save();

            Flash::success('You\'ve added an Event!');

            return Redirect::back();
        }
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
