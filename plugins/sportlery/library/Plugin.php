<?php namespace Sportlery\Library;

use Backend\Models\User;
use Hashids\Hashids;
use Sportlery\Library\Components;
use System\Classes\PluginBase;

class Plugin extends PluginBase
{
    public function registerComponents()
    {
        return [
            Components\LocationList::class => 'locationList',
            Components\LocationProfile::class => 'locationProfile',
            Components\EventList::class => 'eventList',
            Components\EventProfile::class => 'eventProfile',
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
