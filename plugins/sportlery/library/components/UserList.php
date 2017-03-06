<?php

namespace Sportlery\Library\Components;

use Cms\Classes\ComponentBase;
use Cms\Classes\Page;
use Hashids\Hashids;
use Sportlery\Library\Models\User;

class UserList extends ComponentBase
{
    /**
     * Returns information about this component, including name and description.
     */
    public function componentDetails()
    {
        return [
            'name' => 'User List',
            'description' => 'Display a list of users',
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
        $this->page['users'] = $this->users();
        $this->page['detailsPage'] = $this->property('detailsPage');
    }

    public function users()
    {
        $perPage = $this->property('perPage');
        $hashids = \App::make(Hashids::class);

        $query = User::orderBy('last_name', 'asc')->orderBy('first_name', 'asc');

        if ($user = \Auth::getUser()) {
            $query->where('id', '!=', $user->id);
        }

        $users = $query->paginate($perPage);

        $users->each(function($user) use ($hashids) {
            $user->id = $user->getHashId();
        });

        return $users;
    }
}
