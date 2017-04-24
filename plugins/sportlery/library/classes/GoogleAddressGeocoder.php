<?php

namespace Sportlery\Library\Classes;

class GoogleAddressGeocoder
{
    public static function reverseGeocode($lat, $lng)
    {
        $latlng = urlencode("$lat,$lng");
        $url = "https://maps.googleapis.com/maps/api/geocode/json?latlng=$latlng&sensor=false";

        return static::sendRequest($url, ['city' => '', 'zip_code' => '']);
    }

    public static function geocode($input)
    {
        $address = urlencode(implode(' ', $input));
        $url = "https://maps.googleapis.com/maps/api/geocode/json?address=$address&sensor=false";

        return static::sendRequest($url, $input);
    }

    private static function sendRequest($url, $input)
    {
        $data = @file_get_contents($url);

        $json = json_decode($data, true);

        if (!$json || !isset($json['status']) || $json['status'] !== 'OK') {
            return $input;
        }

        $json = $json['results'][0];
        $components = $json['address_components'];
        $country = self::getAddressComponent($components, 'country', 'long_name');
        $zipCode = self::getAddressComponent($components, 'postal_code');
        $city = self::getAddressComponent($components, 'locality');
        $street = self::getAddressComponent($components, 'route');
        $streetNumber = self::getAddressComponent($components, 'street_number');
        $input['street'] = $input['street'] ?? "$street $streetNumber";
        $input['country'] = $country ?: $input['country'];
        $input['zip_code'] = empty(trim($input['zip_code'])) ? $zipCode : $input['zip_code'];
        $input['city'] = ($input['city'] !== $city) ? $city : $input['city'];
        $input['state'] = self::getAddressComponent($components, 'administrative_area_level_1', 'long_name');
        $input['latitude'] = $json['geometry']['location']['lat'];
        $input['longitude'] = $json['geometry']['location']['lng'];

        return $input;
    }

    private static function getAddressComponent($components, $type, $field = 'short_name')
    {
        foreach ($components as $component) {
            if (in_array($type, $component['types'])) {
                return $component[$field];
            }
        }

        return null;
    }
}
