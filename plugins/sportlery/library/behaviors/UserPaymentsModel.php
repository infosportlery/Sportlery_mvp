<?php

namespace Sportlery\Library\Behaviors;

use October\Rain\Database\Model;
use Sportlery\Library\Models\Payment;
use System\Classes\ModelBehavior;

class UserPaymentsModel extends ModelBehavior
{
    public function __construct(Model $model)
    {
        parent::__construct($model);

        $model->hasMany['payments'] = Payment::class;
    }
}
