<?php namespace Rainlab\Blog\Components;

use Cms\Classes\ComponentBase;

class Comments extends ComponentBase
{
    public function componentDetails()
    {
        return [
            'name'        => 'Comments Component',
            'description' => 'Lets users post comments on blog posts.'
        ];
    }

    public function defineProperties()
    {
        return [];
    }

    public function onComment()
    {
        
    }
}
