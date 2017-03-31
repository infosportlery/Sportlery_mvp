<?php namespace Sportlery\Library\Models;

use Model;

/**
 * Model
 */
class LocationTimes extends Model
{
    use \October\Rain\Database\Traits\Validation;
    
    /*
     * Disable timestamps by default.
     * Remove this line if timestamps are defined in the database table.
     */
    public $timestamps = false;

    /*
     * Validation
     */
    public $rules = [
        

    ];

    /**
     * @var string The database table used by the model.
     */
    public $table = 'spr_location_times';
}