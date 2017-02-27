<?php namespace Sportlery\Library\Models;

use Model;

class Sport extends Model
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
    ];

    /**
     * @var string The database table used by the model.
     */
    public $table = 'spr_sports';

    public $belongsToMany = [
        'sports' => [
            Event::class,
            'table' => 'spr_event_sport',
            'order' => 'name'
        ],
    ];
}
