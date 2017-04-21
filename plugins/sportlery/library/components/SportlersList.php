<?php namespace Sportlery\Library\Components;

use Cms\Classes\ComponentBase;
use Cms\Classes\Page;
use Hashids\Hashids;
use Illuminate\Support\Facades\Input;
use Rainlab\User\Models\User;
use Sportlery\Library\Models\Location;
use Sportlery\Library\Models\Sport;
use Auth;

class SportlersList extends ComponentBase
{
    private $searchParameters = [];

    public function componentDetails()
    {
        return [
            'name'        => 'SportlersList Component',
            'description' => 'No description provided yet...'
        ];
    }

    public function defineProperties()
    {
        return [
            'perPage' => [
                'title'             => 'Per page',
                'description'       => 'The number of users displayed on each page',
                'default'           => 10,
                'type'              => 'string',
                'validationPattern' => '^[0-9]+$',
                'validationMessage' => 'The per page field can only contain numbers'
            ],
            'detailsPage' => [
                'title'       => 'Details page',
                'description' => 'The page to redirect to when selecting a User',
                'type'        => 'dropdown',
                'showExternalParam' => false,
            ],
        ];
    }

    public function getDetailsPageOptions()
    {
        return Page::sortBy('baseFileName')->lists('baseFileName', 'baseFileName');
    }

    public function onRun()
    {
        $this->page['sportlers'] = $this->getUsers();
        $this->page['sports'] = $this->getSports();
        $this->page['cities'] = $this->getCities();
        $this->page['detailsPage'] = $this->property('detailsPage');
    }

    private function getSports()
    {
        return Sport::orderBy('name', 'asc')->lists('name', 'id');
    }

    private function getCities()
    {
        return Location::distinct()->orderBy('city', 'asc')->lists('city', 'city');
    }

    private function getUsers()
    {   
        return User::paginate($this->property('perPage'));
    }
}
