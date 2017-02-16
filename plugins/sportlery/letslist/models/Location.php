<?php namespace Sportlery\Letslist\Models;

use Model;

/**
 * Model
 */
class Location extends Model
{
    use \October\Rain\Database\Traits\Validation;
    
    /*
     * Validation
     */
    public $rules = [
    ];

    /**
     * @var string The database table used by the model.
     */
    public $table = 'sportlery_letslist_location';

    /* Relations */ 

    public $attachOne = [
        'avatar' => 'System\Models\File'
    ];

    public $attachMany = [
        'location_gallery' => 'System\Models\File'
    ];

    /* Many To many Location Catagories */

    public $belongsToMany =[

        'catagories' => [
            'sportlery\letslist\Models\Cata',

            'table' => 'sportlery_letslist_location_catas',

            'order' => 'cat_name'

        ],

         'type' => [
            
            'sportlery\letslist\Models\Type',

            'table' => 'sportlery_letslist_location_type',

            'order' => 'name_type'

        ]
    ];



}


