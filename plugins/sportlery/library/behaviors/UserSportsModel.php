<?php

namespace Sportlery\Library\Behaviors;

use System\Classes\ModelBehavior;
use Sportlery\Library\Models\Sport;

class UserSportsModel extends ModelBehavior
{

     public function __construct($model)
    {
        parent::__construct($model);

        $model->belongsToMany['sports'] = [
            Sport::class,
            'table' => 'sportlery_library_user_sports',
            'key' => 'user_id',
            'otherKey' => 'sport_id',
            'pivot' => ['favorite'],
        ];
    }


    
}
