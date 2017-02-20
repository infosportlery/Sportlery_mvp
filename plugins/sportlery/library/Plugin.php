<?php namespace Sportlery\Library;

use Hashids\Hashids;
use Sportlery\Library\Components\LocationList;
use Sportlery\Library\Components\LocationProfile;
use System\Classes\PluginBase;

class Plugin extends PluginBase
{
    public function registerComponents()
    {
        return [
            LocationList::class => 'locationList',
            LocationProfile::class => 'locationProfile',
        ];
    }

    public function registerSettings()
    {
    }

    public function boot()
    {
        \App::singleton(Hashids::class, function() {
            // All lowercase characters.
            $alphabet = 'abcdefghijklmnopqrstuvwxyz0123456789';
            // Set the salt of the hashids to 'sportlery' and the minimum length to 10.
            return new Hashids('sportlery', 10, $alphabet);
        });
    }
}
