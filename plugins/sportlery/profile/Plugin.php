<?php namespace Sportlery\Profile;

use Sportlery\Library\Models\User;
use System\Classes\PluginBase;

use Rainlab\User\Controllers\Users as UsersController;
use Rainlab\User\Models\User as UserModel;

class Plugin extends PluginBase
{
    public function registerComponents()
    {
    }

    public function registerSettings()
    {
    }

    public function boot()
    {
        UsersController::extendFormFields(function ($form, $model, $context) {
            if (starts_with($form->alias, 'relation')) {
                // Don't add profile tab when in the relation form
                return;
            }

            $form->addTabFields([
                'first_name' => [
                    'label' => 'First Name',
                    'type' => 'text',
                    'tab' => 'Profile'
                ],
                'last_name' => [
                    'label' => 'Last Name',
                    'type' => 'text',
                    'tab' => 'Profile'
                ],
                'street' => [
                    'label' => 'Street Name',
                    'type' => 'text',
                    'tab' => 'Profile'
                ],
                'street_num' => [
                    'label' => 'Street Number',
                    'type' => 'text',
                    'tab' => 'Profile'
                ],
                'zip_code' => [
                    'label' => 'Postal/Zip Code',
                    'type' => 'text',
                    'tab' => 'Profile'
                ],
                'city' => [
                    'label' => 'City',
                    'type' => 'text',
                    'tab' => 'Profile'
                ],
                'tel_no' => [
                    'label' => 'Telephone Number',
                    'type' => 'number',
                    'tab' => 'Profile'
                ],
                'bio' => [
                    'label' => 'Biography',
                    'type' => 'textarea',
                    'tab' => 'Profile'
                ],
                'sports' => [
                    'label' => 'Sports',
                    'nameFrom' => 'name',
                    'type' => 'relation',
                    'tab' => 'Profile',
            	],
                'friends' => [
                    'tab' => 'Friends',
                    'type' => 'partial',
                    'path' => '$/sportlery/library/models/friendship/formField.htm',
                ]
            ]);
        });

        UserModel::extend(function($model){
            $model->belongsToMany['sports'] = [
                'Sportlery\Library\Models\Sport',
                'table' => 'sportlery_library_user_sports',
                'order' => 'name',
                'pivot' => ['favorite'],
            ];
        });
    }
}
