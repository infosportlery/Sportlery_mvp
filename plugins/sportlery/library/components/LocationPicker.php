<?php

namespace Sportlery\Library\Components;

use RainLab\Location\Models\Country;
use Auth;
use Validator;
use Input;
use Cms\Classes\ComponentBase;
use RainLab\Location\Models\State;
use Sportlery\Library\Classes\GoogleAddressGeocoder;
use Sportlery\Library\Models\Location;

class LocationPicker extends ComponentBase
{
    /**
     * Returns information about this component, including name and description.
     */
    public function componentDetails()
    {
        return [
            'name' => 'Location picker',
            'description' => 'Display a location picker form',
        ];
    }

    public function onRun()
    {
        $this->addJs('https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.0.3/leaflet.js');
        $this->addCss('https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.0.3/leaflet.css');
    }

    public function onSaveLatLng()
    {
        $rules = [
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ];

        $validator = Validator::make(post(), $rules);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        $result = GoogleAddressGeocoder::reverseGeocode(post('latitude'), post('longitude'));

        return $this->createLocation($result);
    }

    public function onSaveManual()
    {
        $rules = [
            'street' => 'required',
            'city' => 'required',
            'country' => 'required',
        ];

        $validator = Validator::make(post(), $rules);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        $result = GoogleAddressGeocoder::geocode(Input::only('street', 'zip_code', 'city', 'country'));

        return $this->createLocation($result);
    }

    private function createLocation($result)
    {
        $stateId = State::where('name', $result['state'])->pluck('id');
        $countryId = Country::where('name', $result['country'])->pluck('id');
        $randomId = \Str::random(30);
        $location = new Location();
        $location->forceFill([
            'name' => $result['street'].', '.$result['zip_code'].' '.$result['city'].', '.$result['country'],
            'slug' => $randomId,
            'url' => 'https://www.sportlery.nl',
            'description' => '<p></p>',
            'state_id' => $stateId,
            'country_id' => $countryId,
            'zipcode' => $result['zip_code'],
            'street' => $result['street'],
            'city' => $result['city'],
            'latitude' => $result['latitude'],
            'longitude' => $result['longitude'],
            'is_public' => true,
            'is_hidden' => true,
            'user_id' => Auth::getUser()->id ?? null
        ]);
        $location->save();
        return ['id' => $location->id, 'name' => $location->name];
    }

}
