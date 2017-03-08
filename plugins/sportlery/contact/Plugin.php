<?php namespace Sportlery\Contact;

use System\Classes\PluginBase;

class Plugin extends PluginBase
{
    public function registerComponents()
    {
    	return [
    		Components\ContactForm::class => 'contactForm',
    	];
        
    }

    public function registerSettings()
    {
    }
}
