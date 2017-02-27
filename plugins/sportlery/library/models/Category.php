<?php namespace Sportlery\Library\Models;

use Model;

class Category extends Model
{
    use \October\Rain\Database\Traits\Validation;

    public $implement = ['RainLab.Translate.Behaviors.TranslatableModel'];

    public $translatable = ['name'];

    /*
     * Validation
     */
    public $rules = [
        'name' => 'required',
        'slug' => 'required|unique:spr_categories,slug',
        'for' => 'required|in:event,location'
    ];

    /**
     * @var string The database table used by the model.
     */
    public $table = 'spr_categories';

    public $belongsToMany = [
        'locations' => [
            Location::class,
            'table' => 'spr_category_location',
            'order' => 'name',
        ],
        'events' => [
            Event::class,
            'table' => 'spr_category_event',
            'order' => 'name',
        ],
    ];

    public function scopeForLocations($query)
    {
        return $query->where('for', 'location');
    }

    public function scopeForEvents($query)
    {
        return $query->where('for', 'event');
    }
}
