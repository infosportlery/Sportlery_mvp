<?php namespace Sportlery\Library;

use App;
use Cmgmyr\Messenger\MessengerServiceProvider;
use Event;
use Rainlab\User\Models\User;
use Hashids\Hashids;
use RainLab\User\Controllers\Users;
use Sportlery\Library\Components;
use System\Classes\PluginBase;

class Plugin extends PluginBase
{
    public $require = [
        'RainLab.User',
        'RainLab.Location',
        'RainLab.Pages',
        'RainLab.Translate',
    ];

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
            Components\EventForm::class => 'eventForm',
            Components\ChatList::class => 'chatList',
            Components\Chat::class => 'chat',
        ];
    }

    public function registerSettings()
    {
    }

    public function boot()
    {
        $this->bootMessengerPackage();

        App::singleton(Hashids::class, function() {
            // All lowercase characters.
            $alphabet = 'abcdefghijklmnopqrstuvwxyz0123456789';
            // Set the salt of the hashids to 'sportlery' and the minimum length to 10.
            return new Hashids('sportlery', 10, $alphabet);
        });

        Event::listen('locale.changed', function($locale) {
            switch ($locale) {
                case 'nl':
                    $locale = 'nl_NL';
                    break;
                case 'en':
                    $locale = 'en_US';
                    break;
            }

            setlocale(LC_ALL, $locale);
        });

        User::extend(function(User $model) {
            $model->implement[] = 'Sportlery.Library.Behaviors.UserFriendsModel';
            $model->implement[] = 'Sportlery.Library.Behaviors.UserEventsModel';
            $model->implement[] = 'Sportlery.Library.Behaviors.MessagableModel';
            $model->addFillable([
                'street',
                'street_num',
                'zip_code',
                'city',
                'tel_no',
                'bio',
            ]);
        });

        Users::extend(function($controller) {
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

    private function bootMessengerPackage()
    {
        \Config::set('messenger', [
            'user_model' => User::class,
            'messages_table' => 'spr_chat_messages',
            'participants_table' => 'spr_chat_participants',
            'threads_table' => 'spr_chats',
        ]);

        App::register(MessengerServiceProvider::class);
    }
}
