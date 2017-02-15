<?php namespace Sportlery\Letslist\Models;

use Model;

/**
 * Model
 */
class LocationCatagory extends Model
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
    public $table = 'sportlery_letslist_location_catagories';
}