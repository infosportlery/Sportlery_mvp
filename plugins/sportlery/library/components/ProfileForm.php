<?php

namespace Sportlery\Library\Components;

use Cms\Classes\ComponentBase;
use Input;
use Redirect;
use Validator;

class ProfileForm extends ComponentBase {

    public function componentDetails()
    {
        return [
            'name' => 'Profile Form',
            'description' => 'Simple Profile Form',
        ];
    }

    public function onSave()
    {

    }
}
