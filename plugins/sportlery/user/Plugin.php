<?php

namespace Sportlery\User;

use System\Classes\PluginBase;

class Plugin extends PluginBase
{
    public $require = ['RainLab.User'];

    public function pluginDetails()
    {
        dd('oi');
        return [
            'name'        => 'Sportlery User Plugin',
            'description' => 'User plugin extended for Sportlery',
            'author'      => 'Sportlery',
            'icon'        => 'icon-pencil',
            'homepage'    => ''
        ];
    }
}
