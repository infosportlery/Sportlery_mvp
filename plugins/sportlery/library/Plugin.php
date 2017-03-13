<?php namespace Sportlery\Library;

use App;
use Event;
use Rainlab\User\Models\User;
use Hashids\Hashids;
use RainLab\User\Controllers\Users;
use Sportlery\Library\Components;
use System\Classes\PluginBase;

class Plugin extends PluginBase
{
    public function registerComponents()
    {
        return [
            Components\EventList::class => 'eventList',
            Components\EventProfile::class => 'eventProfile',
            Components\FriendList::class => 'friendsList',
            Components\LocationList::class => 'locationList',
            Components\LocationProfile::class => 'locationProfile',
            Components\UserDashboard::class => 'userDashboard',
            Components\UserList::class => 'userList',
            Components\UserProfile::class => 'userProfile',
            Components\UserTickets::class => 'userTickets',
            Components\ProfileForm::class => 'profileForm',
        ];
    }

    public function registerSettings()
    {
    }

    public function boot()
    {
        App::singleton(Hashids::class, function() {
            // All lowercase characters.
            $alphabet = 'abcdefghijklmnopqrstuvwxyz0123456789';
            // Set the salt of the hashids to 'sportlery' and the minimum length to 10.
            return new Hashids('sportlery', 10, $alphabet);
        });

        App::singleton('user.auth', function() {
            return \Sportlery\Library\Classes\AuthManager::instance();
        });

        User::extend(function($model) {
            $model->belongsToMany['friends'] = [
                'Sportlery\Library\Models\User',
                'table' => 'spr_friendships',
                'pivot' => ['status'],
                'timestamps' => true,
                'key' => 'user_id',
                'otherKey' => 'friend_id',
            ];
        });

        Users::extend(function($controller) {
            $controller->formConfig = '$/sportlery/library/overrides/rainlab_user/config_user_form.yaml';
            $controller->listConfig = '$/sportlery/library/overrides/rainlab_user/config_user_list.yaml';
            $controller->implement[] = 'Backend.Behaviors.RelationController';
            $controller->relationConfig = [
                'friends' => [
                    'label' => 'Friends',
                    'view' => [
                        'list' => [
                            'columns' => [
                                'name' => [
                                    'label' => 'Name'
                                ],
                                'pivot[status]' => [
                                    'label' => 'Friendship Status'
                                ]
                            ]
                        ],
                        'toolbarButtons' => 'add|remove'
                    ],
                    'manage' => [
                        'scope' => 'withoutUser'
                    ],
                    'pivot' => [
                        'form' => [
                            'fields' => [
                                'pivot[status]' => [
                                    'label' => 'Status',
                                    'span' => 'auto',
                                    'type' => 'radio',
                                    'default' => 0,
                                    'options' => [
                                        0 => 'Pending',
                                        1 => 'Accepted',
                                        2 => 'Denied',
                                        3 => 'Blocked',
                                    ]
                                ],
                            ]
                        ],
                    ],
                ]
            ];
        });
    }
}
