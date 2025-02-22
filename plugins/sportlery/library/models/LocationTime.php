<?php

namespace Sportlery\Library\Models;

use Model;

class LocationTime extends Model
{
    use \October\Rain\Database\Traits\Validation;

    /*
     * Validation
     */
    public $rules = [
        'monday_start' => 'before:monday_end',
        'tuesday_start' => 'before:tuesday_end',
        'wednesday_start' => 'before:wednesday_end',
        'thursday_start' => 'before:thursday_end',
        'friday_start' => 'before:friday_end',
        'saturday_start' => 'before:saturday_end',
        'sunday_start' => 'before:sunday_end',
    ];

    /**
     * @var string The database table used by the model.
     */
    public $table = 'spr_location_times';

    public function filterFields($fields, $context = null)
    {
        if ($context === 'relation') {
            $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
            foreach ($days as $day) {
                $start = "{$day}_start";
                $end = "{$day}_end";
                if (!$fields->$start->value || !$fields->$end->value) {
                    $fields->$start->label = ucfirst($day);
                    $fields->$start->type = 'text';
                    $fields->$start->value = 'Closed';
                    $fields->$end->hidden = true;
                }
            }
        }
    }
}
