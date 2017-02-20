<?php namespace Sportlery\Library\Models;

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
    public $rules = [];

    /**
     * @var string The database table used by the model.
     */
    public $table = 'spr_locations';

    /* Relations */
    public $attachOne = [
        'avatar' => 'System\Models\File'
    ];

    public $attachMany = [
        'location_gallery' => 'System\Models\File'
    ];

    public $belongsToMany = [
        'categories' => [
            Category::class,
            'table' => 'spr_category_location',
            'order' => 'name'
        ],
    ];
}


