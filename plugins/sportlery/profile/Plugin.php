<?php namespace Sportlery\Profile;

use System\Classes\PluginBase;

use Rainlab\User\Controllers\Users as UsersController;

class Plugin extends PluginBase
{
    public function registerComponents()
    {
    }

    public function registerSettings()
    {
    }

    public function boot() {

    	UsersController::extendFormFields(function($form, $model, $context){
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
    				]
    			]);
    	});

    }
}
