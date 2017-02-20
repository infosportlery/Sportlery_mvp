<?php namespace Sportlery\Library\Models;

use Model;

/**
 * Model
 */
class Location extends Model
{
    use \October\Rain\Database\Traits\Validation;
    use \Sportlery\Library\Classes\Traits\HashIds;

    public $implement = ['RainLab.Translate.Behaviors.TranslatableModel'];

    public $translatable = ['description'];

    /*
     * Validation
     */
    public $rules = [
        'name' => 'required',
        'street' => 'required',
        'email' => 'required|email',
        'zipcode' => 'required',
        'city' => 'required',
        'location_url' => 'url',
        'slug' => 'required|alpha_dash|unique:spr_locations,slug',
        'is_public' => 'required|in:0,1',
        'description' => 'required',
    ];


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
            'order' => 'name',
            'scope' => 'forLocations',
        ],
    ];

    /**
     * Get an array of category names attached to the event.
     *
     * @return array
     */
    public function getCategoryNamesAttribute()
    {
        return $this->categories->lists('name');
    }
}


