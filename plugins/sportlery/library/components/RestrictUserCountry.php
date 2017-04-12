<?php

namespace Sportlery\Library\Components;

use Cms\Classes\ComponentBase;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Session;

class RestrictUserCountry extends ComponentBase
{
    /**
     * Returns information about this component, including name and description.
     */
    public function componentDetails()
    {
        return [
            'name' => 'Restrict user country',
            'description' => 'Restrict page access to a given user country code'
        ];
    }

    public function defineProperties()
    {
        return [
            'countryCode' => [
                'title'             => 'Country code',
                'description'       => 'The country code to restrict the access to',
                'default'           => 'NL',
                'type'              => 'string',
                'validationPattern' => '^[A-Z]{2}$',
                'validationMessage' => 'The country code should be 2 capital letters.'
            ],
        ];
    }

    public function onRun()
    {
        $this->page['userCountryAccess'] = $this->checkUserHasAccess($this->property('countryCode'));
    }

    private function checkUserHasAccess($countryCode)
    {
        if (Session::has('user_country_code')) {
            return Session::get('user_country_code') === $countryCode;
        }

        $userIp = Request::ip();
        $result = @file_get_contents("https://freegeoip.net/json/{$userIp}");
        $data = json_decode($result, true);
        $userCountry = array_get($data, 'country_code');

        if (!$userCountry) {
            // Either the request to the api failed, the response did not contain valid json
            // or it did not contain the country_code, so just let the user pass through.
            return true;
        }

        // Store the user country so that we don't have to reach out to the api every time.
        Session::set('user_country_code', $userCountry);

        return $userCountry === $countryCode;
    }
}
